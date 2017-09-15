<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of UserManager
 *
 * @Route("admin")
 */
class UserController extends Controller
{

    /**
     * Lists all users entities.
     *
     * @Route("/users/{page}", name="admin_users", defaults={"page" = 1}, requirements={
     *  "page" = "\d+",
     * })
     * @Method("GET")
     */
    public function indexAction($page)
    {
        return $this->render('AdminBundle:User:index.html.twig', [
                    'entities' => $this->get('user.manager')->findUsers($page, true)
        ]);
    }

}
