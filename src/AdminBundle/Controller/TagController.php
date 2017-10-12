<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\TagType;

/**
 * Description of TagController
 *
 * @Route("/admin")
 */
class TagController extends Controller
{

    /**
     * Lists all tags entities
     * 
     * @Route("/tags/{page}", name="admin_tags", defaults={"page" = 1}, requirements={
     *  "page" = "\d+"
     * })
     * @Method({"GET", "POST"})
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $tagMng = $this->get('tag.manager');
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag, ['validation_groups' => array('single')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagMng->persist($tag)->flush();
            return $this->redirectToRoute('admin_tags');
        }

        return $this->render('AdminBundle:Tag:index.html.twig', [
                    'entities' => $tagMng->findTags(),
                    'form' => $form->createView(),
                    'delete_form' => $this->createDeleteForm()->createView(),
        ]);
    }

    /**
     * Deletes a tag entity.
     *
     * @Route("/tag/{id}/delete", name="admin_tag_delete", requirements={
     *  "id" = "\d+"
     * })
     * @Method("DELETE")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Tag $tag)
    {
        $form = $this->createDeleteForm($tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('tag.manager')->deleteEntity($tag);
            $this->addFlash('danger', 'tag "' . $tag->getName() . '" removed');
        }else{
//            dump($form->getErrors());
//            exit;
        }

        return $this->redirectToRoute('admin_tags');
    }

    /**
     * Creates a form to delete a tag entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
