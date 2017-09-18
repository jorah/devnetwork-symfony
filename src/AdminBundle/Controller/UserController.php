<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of UserManager
 *
 * @Route("admin")
 */
class UserController extends Controller
{

    /**
     * Lists all users entities.
     *
     * @Route("/users/{page}", name="admin_users", defaults={"page" = 1}, requirements={
     *  "page" = "\d+",
     * })
     * @Method("GET")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $page
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page)
    {
        $sanitize = $this->get('sanitize_request');
        $criteria = $sanitize->sanitize($request->query->all());
        if ($criteria['error']) {
            foreach ($criteria['error'] as $err) {
                $this->addFlash('danger', $err);
                $criteria['data'] = $sanitize->getDefault();
            }
        }
        
        return $this->render('AdminBundle:User:index.html.twig', [
                    'entities' => $this->get('user.manager')->findUsers($page, $criteria['data'], true),
        ]);
    }

    /**
     * Display a code entity with related entities
     * 
     * @Route("/user/{id}/edit", name="admin_user", requirements={
     *      "id" = "\d+"
     * })
     * @Method("GET")
     * 
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {

        $user = $this->get('user.manager')->showUser($id);
        if (!$user) {
            return $this->createNotFoundException('User entity #' . $id . ' not found');
        }

        return $this->render('AdminBundle:User:edit.html.twig', [
                    'entity' => $user,
                    'deleteHard_form' => $this->createDeleteForm($user, 'danger')->createView(),
                    'deleteSoft_form' => $this->createDeleteForm($user, 'warning')->createView(),
                    'active_form' => $this->createDeleteForm($user, 'info')->createView(),
                    'promote_form' => $this->createPromoteForm($user, 'admin')->createView(),
                    'demote_form' => $this->createPromoteForm($user, 'user')->createView()
        ]);
    }

    /**
     * Promote/Demote an user
     * 
     * @Route("/user/{id}/role/{role}", name="admin_user_role", requirements={
     *      "id" = "\d+",
     *      "role" = "user|admin",
     * })
     * @Method("POST")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @param string $role
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateRole(Request $request, User $user, $role)
    {
        $form = $this->createPromoteForm($user, $role);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('user.manager')->promoteUser($user, $role);
        }
        
        return $this->redirectToRoute('admin_user', ['id' => $user->getId()]);
    }

    /**
     * Delete(Hard/Soft) or activate an user entity
     * 
     * @Route("/user/{id}/delete/{type}", name="admin_user_delete", requirements={
     *      "id" = "\d+",
     *      "type" = "danger|warning|info",
     * })
     * @Method("DELETE")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @param string $type
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, User $user, $type)
    {

        $form = $this->createDeleteForm($user, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('user.manager')->deleteCode($user, $type);
            $this->addFlash($type, 'Code #' . $user->getId() . ' ' . ($type == 'info' ? 'activé.' : 'supprimé.'));
        }

        if ($type == 'danger') {
            return $this->redirectToRoute('admin_users');
        } else {
            return $this->redirectToRoute('admin_user', ['id' => $user->getId()]);
        }
    }

    /**
     * Creates a form to delete or activate an user
     *
     * @param \AppBundle\Entity\User $user
     * @param string $type
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user, $type)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_user_delete', [
                                    'id' => $user->getId(),
                                    'type' => $type
                        ]))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Creates a form to promote or demote an user
     *
     * @param \AppBundle\Entity\User $user
     * @param string $role
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createPromoteForm(User $user, $role)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_user_role', [
                                    'id' => $user->getId(),
                                    'role' => $role
                        ]))
                        ->setMethod('POST')
                        ->getForm()
        ;
    }

}
