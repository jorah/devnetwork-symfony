<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Language controller.
 *
 * @Route("admin")
 */
class LanguageController extends Controller
{

    /**
     * Lists all language entities.
     *
     * @Route("/languages/{page}", name="admin_languages", defaults={"page" = 1}, requirements={
     *  "page" = "\d+"
     * })
     * @Method("GET")
     */
    public function indexAction($page)
    {
        return $this->render('AdminBundle:Language:index.html.twig', [
                    'entities' => $this->get('language.manager')->findLanguages($page)
        ]);
    }

    /**
     * Creates a new language entity.
     *
     * @Route("/language/new", name="admin_language_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $language = new Language();
        $form = $this->createForm('AppBundle\Form\LanguageType', $language);
        $form->handleRequest($request);
        
//        if ($form->isSubmitted()) {
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($language);
//                $em->flush();
//                return $this->redirectToRoute('admin_languages');
//            }
//        }
        


        return $this->render('AdminBundle:Language:new.html.twig', array(
                    'language' => $language,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a language entity.
     *
     * @param Language $language The language entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Language $language)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('language_delete', array('id' => $language->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
