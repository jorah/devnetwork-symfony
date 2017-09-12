<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Code;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Code controller.
 *
 * @Route("code")
 */
class CodeController extends Controller
{
    /**
     * Lists all code entities.
     *
     * @Route("/", name="code_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $codes = $em->getRepository('AppBundle:Code')->findAll();

        return $this->render('code/index.html.twig', array(
            'codes' => $codes,
        ));
    }

    /**
     * Creates a new code entity.
     *
     * @Route("/new", name="code_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $code = new Code();
        $form = $this->createForm('AppBundle\Form\CodeType', $code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($code);
            $em->flush();

            return $this->redirectToRoute('code_show', array('id' => $code->getId()));
        }

        return $this->render('code/new.html.twig', array(
            'code' => $code,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a code entity.
     *
     * @Route("/{id}", name="code_show")
     * @Method("GET")
     */
    public function showAction(Code $code)
    {
        $deleteForm = $this->createDeleteForm($code);

        return $this->render('code/show.html.twig', array(
            'code' => $code,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing code entity.
     *
     * @Route("/{id}/edit", name="code_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Code $code)
    {
        $deleteForm = $this->createDeleteForm($code);
        $editForm = $this->createForm('AppBundle\Form\CodeType', $code);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('code_edit', array('id' => $code->getId()));
        }

        return $this->render('code/edit.html.twig', array(
            'code' => $code,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a code entity.
     *
     * @Route("/{id}", name="code_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Code $code)
    {
        $form = $this->createDeleteForm($code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($code);
            $em->flush();
        }

        return $this->redirectToRoute('code_index');
    }

    /**
     * Creates a form to delete a code entity.
     *
     * @param Code $code The code entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Code $code)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('code_delete', array('id' => $code->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
