<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

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
     */
    public function indexAction($page)
    {
        return $this->render('AdminBundle:User:index.html.twig', [
                    'entities' => $this->get('user.manager')->findUsers($page, true)
        ]);
    }

    /**
     * Display a code entity with related entities
     * 
     * @Route("/user/{id}", name="admin_user", requirements={
     *      "id" = "\d+"
     * })
     * @Method("GET")
     * 
     * @return Response
     */
    public function showAction(User $user)
    {
        $this->get('user.manager')->test($user);


        return $this->render('AdminBundle:User:show.html.twig', [
                    'entity' => $user
        ]);
    }

    /**
     * Display a code entity with related entities
     * 
     * @Route("/user/{id}/role/{role}", name="admin_user_role", requirements={
     *      "id" = "\d+",
     *      "role" = "user|admin",
     * })
     * @Method("POST")
     * 
     * @return Response
     */
    public function updateRole(User $user, $role)
    {
//        $this->get('user.manager')->promoteUser($user, $role);

        dump($user);
        exit;
//        $this->get('user.manager')->test($user);

        return $this->redirectToRoute('admin_user', ['id' => $user->getId()]);
    }

    /**
     * Delete a code entity
     * 
     * @Route("/user/delete/{type}/{id}", name="admin_user_delete", requirements={
     *      "id" = "\d+",
     *      "type" = "danger|warning|info",
     * })
     * @Method("DELETE")
     * 
     * @param string $type
     * @param int $id
     * 
     * @return Redirect
     */
    public function deleteAction($type, User $user)
    {
        $this->get('user.manager')->deleteCode($user, $type);
        $this->addFlash($type, 'Code #' . $user->getId() . ' ' . ($type == 'info' ? 'activé.' : 'supprimé.'));

        if ($type == 'danger') {
            return $this->redirectToRoute('admin_users');
        } else {
            return $this->redirectToRoute('admin_user', ['id' => $user->getId()]);
        }
    }

}
