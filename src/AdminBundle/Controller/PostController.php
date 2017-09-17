<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of PostController
 *
 * @Route("/admin")
 */
class PostController extends Controller
{

    /**
     * Lists all posts entities.
     *
     * @Route("/posts/{page}", name="admin_posts", defaults={"page" = 1}, requirements={
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
        $postMng = $this->get('post.manager');
        $sanitize = $this->get('sanitize_request');

        $criteria = $sanitize->sanitize($request->query->all());
        if ($criteria['error']) {
            foreach ($criteria['error'] as $err) {
                $this->addFlash('danger', $err);
                $criteria['data'] = $sanitize->getDefault();
            }
        }

        return $this->render('AdminBundle:Post:index.html.twig', [
                    'entities' => $postMng->findPosts($page, $criteria['data']),
        ]);
    }

    /**
     * Display a post entity with related entities
     * 
     * @Route("/post/{id}/edit", name="admin_post", requirements={
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
        $post = $this->get('post.manager')->showPost($id);
        if (!$post) {
            return $this->createNotFoundException('Post entity #' . $id . ' not found');
        }

        return $this->render('AdminBundle:Post:edit.html.twig', [
                    'entity' => $post,
                    'deleteHard_form' => $this->createDeleteForm($post, 'danger')->createView(),
                    'deleteSoft_form' => $this->createDeleteForm($post, 'warning')->createView(),
                    'active_form' => $this->createDeleteForm($post, 'info')->createView(),
        ]);
    }

    /**
     * Delete a post entity
     * 
     * @Route("/post/{id}/delete/{type}", name="admin_post_delete", requirements={
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
    public function deleteAction(Request $request, $type, Post $post)
    {
        $form = $this->createDeleteForm($post, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('post.manager')->deletePost($post, $type);
            $this->addFlash($type, 'Post #' . $post->getId() . ' ' . ($type == 'info' ? 'activé.' : 'supprimé.'));
        }
        
        if ($type == 'danger') {
            return $this->redirectToRoute('admin_posts');
        } else {
            return $this->redirectToRoute('admin_post', ['id' => $post->getId()]);
        }
    }

    /**
     * Creates a form to delete or activate a post entity
     *
     * @param \AppBundle\Entity\Post $post
     * @param string $type
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Post $post, $type)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_post_delete', [
                                    'id' => $post->getId(),
                                    'type' => $type
                        ]))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
