<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin Default Controller
 *
 * @Route("admin")
 */
class DefaultController extends Controller
{
    /**
     * Lists all language entities.
     *
     * @Route("/", name="admin_homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }
}
