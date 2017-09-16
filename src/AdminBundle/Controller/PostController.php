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
     * @Route("/post/{id}", name="admin_post", requirements={
     *      "id" = "\d+"
     * })
     * @Method("GET")
     * 
     * @return Response
     */
    public function showAction($id)
    {
        $entity = $this->get('post.manager')->showPost($id);
        if (!$entity) {
            return $this->createNotFoundException('Post entity #' . $id . ' not found');
        }

        return $this->render('AdminBundle:Post:show.html.twig', [
                    'entity' => $entity
        ]);
    }
    
    /**
     * Delete a post entity
     * 
     * @Route("/post/delete/{type}/{id}", name="admin_post_delete", requirements={
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
    public function deleteAction($type, Post $post)
    {
        $this->get('post.manager')->deletePost($post, $type);
        $this->addFlash($type, 'Post #' . $post->getId() . ' '.($type == 'info' ? 'activé.' : 'supprimé.'));
        
        if($type == 'danger'){
        return $this->redirectToRoute('admin_posts');
        } else{
            return $this->redirectToRoute('admin_post', ['id' => $post->getId()]);
        }
    }
}
