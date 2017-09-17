<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ThemeType;

/**
 * Description of ThemeController
 *
 * @Route("/admin")
 */
class ThemeController extends Controller
{

    /**
     * Lists all themes entities
     * 
     * @Route("/themes/{page}", name="admin_themes", defaults={"page" = 1}, requirements={
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
        $themeMng = $this->get('theme.manager');
        $theme = new Theme();

        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $themeMng->persist($theme)->flush();
            return $this->redirectToRoute('admin_themes');
        }

        return $this->render('AdminBundle:Theme:index.html.twig', [
                    'entities' => $themeMng->findThemes(),
                    'form' => $form->createView(),
                    'delete_form' => $this->createDeleteForm()->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing entity entity.
     * Display a form to delete this entity
     *
     * @Route("/theme/{id}/edit", name="admin_theme_edit")
     * @Method({"GET", "POST"})
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|
     *      \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, Theme $theme)
    {
        $themeMng = $this->get('theme.manager');
        $current_image = $theme->getImage();
        $fileUploader = $this->get('file_uploader')->set('theme');
        $theme->setImage($fileUploader->check($theme->getImage()));

        $deleteForm = $this->createDeleteForm($theme);
        $editForm = $this->createForm('AppBundle\Form\ThemeType', $theme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $theme->setImage($fileUploader->upload($theme->getImage()));
            $themeMng->save($theme);

            return $this->redirectToRoute('admin_themes');
        }

        return $this->render('AdminBundle:Theme:edit.html.twig', array(
                    'entity' => $theme,
                    'image' => $current_image,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a theme entity.
     *
     * @Route("/theme/delete/{id}", name="admin_theme_delete", requirements={
     *  "id" = "\d+"
     * })
     * @Method("DELETE")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Theme $theme)
    {
        $form = $this->createDeleteForm($theme);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->get('theme.manager')->deleteEntity($theme);
            $this->addFlash('danger', 'theme "' . $theme->getName() . '" removed');
        }

        return $this->redirectToRoute('admin_themes');
    }

    /**
     * Creates a form to delete a theme entity.
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
