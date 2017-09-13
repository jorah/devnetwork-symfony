<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommentCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Commentcode controller.
 *
 * @Route("commentcode")
 */
class CommentCodeController extends Controller
{
    /**
     * Lists all commentCode entities.
     *
     * @Route("/", name="commentcode_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentCodes = $em->getRepository('AppBundle:CommentCode')->findAll();

        return $this->render('AppBundle:CommentCode:index.html.twig', array(
            'commentCodes' => $commentCodes,
        ));
    }

    /**
     * Creates a new commentCode entity.
     *
     * @Route("/new", name="commentcode_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $commentCode = new Commentcode();
        $form = $this->createForm('AppBundle\Form\CommentCodeType', $commentCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentCode);
            $em->flush();

            return $this->redirectToRoute('commentcode_show', array('id' => $commentCode->getId()));
        }

        return $this->render('AppBundle:CommentCode:new.html.twig', array(
            'commentCode' => $commentCode,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a commentCode entity.
     *
     * @Route("/{id}", name="commentcode_show")
     * @Method("GET")
     */
    public function showAction(CommentCode $commentCode)
    {
        $deleteForm = $this->createDeleteForm($commentCode);

        return $this->render('AppBundle:CommentCode:show.html.twig', array(
            'commentCode' => $commentCode,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commentCode entity.
     *
     * @Route("/{id}/edit", name="commentcode_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CommentCode $commentCode)
    {
        $deleteForm = $this->createDeleteForm($commentCode);
        $editForm = $this->createForm('AppBundle\Form\CommentCodeType', $commentCode);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentcode_edit', array('id' => $commentCode->getId()));
        }

        return $this->render('AppBundle:CommentCode:edit.html.twig', array(
            'commentCode' => $commentCode,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commentCode entity.
     *
     * @Route("/{id}", name="commentcode_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CommentCode $commentCode)
    {
        $form = $this->createDeleteForm($commentCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentCode);
            $em->flush();
        }

        return $this->redirectToRoute('commentcode_index');
    }

    /**
     * Creates a form to delete a commentCode entity.
     *
     * @param CommentCode $commentCode The commentCode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CommentCode $commentCode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentcode_delete', array('id' => $commentCode->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
