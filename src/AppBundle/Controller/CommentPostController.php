<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommentPost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Commentpost controller.
 *
 * @Route("commentpost")
 */
class CommentPostController extends Controller
{
    /**
     * Lists all commentPost entities.
     *
     * @Route("/", name="commentpost_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentPosts = $em->getRepository('AppBundle:CommentPost')->findAll();

        return $this->render('commentpost/index.html.twig', array(
            'commentPosts' => $commentPosts,
        ));
    }

    /**
     * Creates a new commentPost entity.
     *
     * @Route("/new", name="commentpost_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $commentPost = new Commentpost();
        $form = $this->createForm('AppBundle\Form\CommentPostType', $commentPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentPost);
            $em->flush();

            return $this->redirectToRoute('commentpost_show', array('id' => $commentPost->getId()));
        }

        return $this->render('commentpost/new.html.twig', array(
            'commentPost' => $commentPost,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a commentPost entity.
     *
     * @Route("/{id}", name="commentpost_show")
     * @Method("GET")
     */
    public function showAction(CommentPost $commentPost)
    {
        $deleteForm = $this->createDeleteForm($commentPost);

        return $this->render('commentpost/show.html.twig', array(
            'commentPost' => $commentPost,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commentPost entity.
     *
     * @Route("/{id}/edit", name="commentpost_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CommentPost $commentPost)
    {
        $deleteForm = $this->createDeleteForm($commentPost);
        $editForm = $this->createForm('AppBundle\Form\CommentPostType', $commentPost);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentpost_edit', array('id' => $commentPost->getId()));
        }

        return $this->render('commentpost/edit.html.twig', array(
            'commentPost' => $commentPost,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commentPost entity.
     *
     * @Route("/{id}", name="commentpost_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CommentPost $commentPost)
    {
        $form = $this->createDeleteForm($commentPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentPost);
            $em->flush();
        }

        return $this->redirectToRoute('commentpost_index');
    }

    /**
     * Creates a form to delete a commentPost entity.
     *
     * @param CommentPost $commentPost The commentPost entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CommentPost $commentPost)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentpost_delete', array('id' => $commentPost->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
