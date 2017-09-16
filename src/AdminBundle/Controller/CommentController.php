<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\CommentCode;
use AppBundle\Entity\CommentPost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CommentController
 *
 * @Route("/admin")
 */
class CommentController extends Controller
{

    /**
     * Lists all comments entities.
     *
     * @Route("/comments/{type}/{page}", name="admin_comments", defaults={"page" = 1}, requirements={
     *  "page" = "\d+",
     *  "type" = "code|post"
     * })
     * @Method("GET")
     */
    public function indexAction(Request $request, $type, $page)
    {
        $comMng = $this->get('comment.manager')->set($type);
        $sanitize = $this->get('sanitize_request');

        $criteria = $sanitize->sanitize($request->query->all());
        if ($criteria['error']) {
            foreach ($criteria['error'] as $err) {
                $this->addFlash('danger', $err);
                $criteria['data'] = $sanitize->getDefault();
            }
        }

        return $this->render('AdminBundle:Comment:index.html.twig', [
                    'entities' => $comMng->findComments($page, $criteria['data']),
                    'type' => $type
        ]);
    }

    /**
     * Delete a comment
     * 
     * @Route("/comment/{type}/delete/{id}", name="admin_comment_delete", requirements={
     *      "id" = "\d+",
     *      "type" = "code|post",
     * })
     * @Method("DELETE")
     * 
     * @param string $type
     * @param int $id
     * 
     * @return Redirect
     */
    public function deleteAction($type, $id)
    {
        $status = $this->get('comment.manager')->set($type)->deleteComment($id);
        if(!$status){
            return $this->createNotFoundException('Commentaire #'.$id.' ('.$type.') non trouvé');
        }
        $this->addFlash('danger', 'Commentaire '.$type.' #' . $id . ' supprimé.');

        return $this->redirectToRoute('admin_comments', ['type' => $type]);
    }

}
