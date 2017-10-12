<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Code;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('AppBundle:Code:index.html.twig', array(
                    'codes' => $codes,
        ));
    }

    /**
     * Creates a new code entity.
     *
     * @Route("/new", name="code_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     * 
     * @todo manage tags submition
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function newAction(Request $request)
    {
        $code = new Code();
        $form = $this->createForm('AppBundle\Form\CodeType', $code);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // add current user to the post
            $code->setUser($this->getUser());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($code);
                $em->flush();
                return $this->redirectToRoute('code_show', array('id' => $code->getId()));
            }
        }



        return $this->render('AppBundle:Code:new.html.twig', array(
                    'code' => $code,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a code entity.
     *
     * @Route("/{id}", name="code_show")
     * @Method("GET")
     * 
     */
    public function showAction(Code $code)
    {
        $deleteForm = $this->createDeleteForm($code);

        return $this->render('AppBundle:Code:show.html.twig', array(
                    'code' => $code,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing code entity.
     *
     * @Route("/{id}/edit", name="code_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     * 
     * @todo manage tags submition
     * 
     * @param Request $request
     * @param Code $code
     * 
     * @return Response
     */
    public function editAction(Request $request, Code $code)
    {
        // check if user is post owner
        
        if ($this->getUser()->getId() != $code->getUser()->getId()) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $tagsCollection = $code->getTags();
        $tags = [];
        foreach ($tagsCollection as $tag){
            echo $tag->getName();
        }
//        dump($tags);
//        exit;
        $deleteForm = $this->createDeleteForm($code);
        $editForm = $this->createForm('AppBundle\Form\CodeType', $code);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            if ($editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();
            }else{
                dump($editForm->getErrors());exit;
            }

            return $this->redirectToRoute('code_edit', array('id' => $code->getId()));
        }

        return $this->render('AppBundle:Code:edit.html.twig', array(
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
