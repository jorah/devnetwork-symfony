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
 * @Route("/admin")
 */
class LanguageController extends Controller
{

    /**
     * Lists all language entities.
     *
     * @Route("/languages", name="admin_languages")
     * @Method({"GET", "POST"})
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $langMng = $this->get('language.manager');
        $language = new Language();
        $form = $this->createForm('AppBundle\Form\LanguageType', $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $langMng->persist($language)->flush();
            $this->addFlash('success', 'Le language "'.$language->getName().'" a été ajouté');
        }

        return $this->render('AdminBundle:Language:index.html.twig', [
                    'entities' => $langMng->findLanguagesByStat(),
                    'language' => $language,
                    'form' => $form->createView(),
                    'delete_form' => $this->createDeleteForm()->createView()
        ]);
    }

    /**
     * Deletes a language entity.
     *
     * @Route("/language/{id}/delete", name="admin_language_delete", requirements={
     *  "id" = "\d+"
     * })
     * @Method("DELETE")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Language $language)
    {
        if ($this->get('code.manager')->hasLanguage($language)) {
            $this->addFlash('danger', 'Some codes still using the language "' . $language->getName() . '".');
            return $this->redirectToRoute('admin_codes', ['language' => $language->getName()]);
        }


        $form = $this->createDeleteForm($language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $langMng = $this->get('language.manager');
            $langMng->deleteEntity($language);
            $this->addFlash('danger', 'programming languege "' . $language->getName() . '" removed');
        }

        return $this->redirectToRoute('admin_languages');
    }

    /**
     * Creates a form to delete a language entity.
     *
     * @param Language $language The language entity
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
