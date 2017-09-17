<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Code;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CodeController
 *
 * @Route("/admin")
 */
class CodeController extends Controller
{

    /**
     * Lists all codes entities.
     *
     * @Route("/codes/{page}", name="admin_codes", defaults={"page" = 1}, requirements={
     *  "page" = "\d+"
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
        $codeMng = $this->get('code.manager');
        $langMng = $this->get('language.manager');
        $sanitize = $this->get('sanitize_request');

        $criteria = $sanitize->sanitize($request->query->all());
        if ($criteria['error']) {
            foreach ($criteria['error'] as $err) {
                $this->addFlash('danger', $err);
                $criteria['data'] = $sanitize->getDefault();
            }
        }

        return $this->render('AdminBundle:Code:index.html.twig', [
                    'entities' => $codeMng->findCodes($page, $criteria['data']),
                    'options' => $langMng->showOption($criteria['data']['language'])
        ]);
    }

    /**
     * Display a code entity with related entities
     * 
     * @Route("/code/{id}/edit", name="admin_code", requirements={
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
        $code = $this->get('code.manager')->showCode($id);
        if (!$code) {
            return $this->createNotFoundException('Code entity #' . $id . ' not found');
        }

        return $this->render('AdminBundle:Code:edit.html.twig', [
                    'entity' => $code,
                    'deleteHard_form' => $this->createDeleteForm($code, 'danger')->createView(),
                    'deleteSoft_form' => $this->createDeleteForm($code, 'warning')->createView(),
                    'active_form' => $this->createDeleteForm($code, 'info')->createView(),
        ]);
    }

    /**
     * Delete a code entity
     * 
     * @Route("/code/{id}/delete/{type}", name="admin_code_delete", requirements={
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
    public function deleteAction(Request $request, Code $code, $type)
    {
        $form = $this->createDeleteForm($code, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('code.manager')->deleteCode($code, $type);
            $this->addFlash($type, 'Code #' . $code->getId() . ' ' . ($type == 'info' ? 'activé.' : 'supprimé.'));
        }

        if ($type == 'danger') {
            return $this->redirectToRoute('admin_codes');
        } else {
            return $this->redirectToRoute('admin_code', ['id' => $code->getId()]);
        }
    }

    /**
     * Creates a form to delete or activate a code entity
     *
     * @param \AppBundle\Entity\Code $code
     * @param string $type
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Code $code, $type)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_code_delete', [
                                    'id' => $code->getId(),
                                    'type' => $type
                        ]))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
