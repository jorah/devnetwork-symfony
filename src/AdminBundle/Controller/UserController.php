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
     * Lists all language entities.
     *
     * @Route("/users/{page}", name="admin_users", defaults={"page" = 1}, requirements={
     *  "page" = "\d+"
     * })
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $qbUsers = $this->get('user.manager')->findUsers(true);
        $users = $this->get('knp_paginator')->paginate(
                $qbUsers, $page, 1, ['distinct' => true]
        );
        return $this->render('AdminBundle:User:index.html.twig', [
                    'users' => $users
        ]);
    }

}
