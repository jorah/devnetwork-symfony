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
     * @Route("/code/{id}", name="admin_code", requirements={
     *      "id" = "\d+"
     * })
     * @Method("GET")
     * 
     * @return Response
     */
    public function showAction($id)
    {
        $entity = $this->get('code.manager')->showCode($id);
        if (!$entity) {
            return $this->createNotFoundException('Code entity #' . $id . ' not found');
        }

        return $this->render('AdminBundle:Code:show.html.twig', [
                    'entity' => $entity
        ]);
    }

    /**
     * Delete a code entity
     * 
     * @Route("/code/delete/{type}/{id}", name="admin_code_delete", requirements={
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
    public function deleteAction($type, Code $code)
    {
        $this->get('code.manager')->deleteCode($code, $type);
        $this->addFlash($type, 'Code #' . $code->getId() . ' '.($type == 'info' ? 'activé.' : 'supprimé.'));
        
        if($type == 'danger'){
        return $this->redirectToRoute('admin_codes');
        } else{
            return $this->redirectToRoute('admin_code', ['id' => $code->getId()]);
        }
    }

}
