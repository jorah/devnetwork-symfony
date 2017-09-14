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
     * @Route("/users", name="admin_users")
     * @Method("GET")
     */
    public function indexAction()
    {
        $users = $this->get('user.manager')->findUsers(true);
        return $this->render('AdminBundle:User:index.html.twig');
    }
    
}
