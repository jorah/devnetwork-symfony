<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Skill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SkillType;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Description of SkillController
 *
 * @Route("/admin")
 */
class SkillController extends Controller
{

    /**
     * Lists all skills entities
     * 
     * @Route("/skills/{page}", name="admin_skills", defaults={"page" = 1}, requirements={
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
        $skillMng = $this->get('skill.manager');
        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileUploader = $this->get('file_uploader')->set('skill');
            $skill->setImage($fileUploader->upload($skill->getImage()));
            $skillMng->save($skill);
            return $this->redirectToRoute('admin_skills');
        }

        return $this->render('AdminBundle:Skill:index.html.twig', [
                    'entities' => $skillMng->findSkills(),
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing entity entity.
     * Display a form to delete this entity
     *
     * @Route("/skill/{id}/edit", name="admin_skill_edit")
     * @Method({"GET", "POST"})
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|
     *      \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, Skill $skill)
    {
        $skillMng = $this->get('skill.manager');
        $current_image = $skill->getImage();
        $fileUploader = $this->get('file_uploader')->set('skill');
        $skill->setImage($fileUploader->check($skill->getImage()));

        $deleteForm = $this->createDeleteForm($skill);
        $editForm = $this->createForm('AppBundle\Form\SkillType', $skill);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $skill->setImage($fileUploader->upload($skill->getImage()));
            $skillMng->save($skill);

            return $this->redirectToRoute('admin_skills');
        }

        return $this->render('AdminBundle:Skill:edit.html.twig', array(
                    'entity' => $skill,
                    'image' => $current_image,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a skill entity.
     *
     * @Route("/skill/delete/{id}", name="admin_skill_delete", requirements={
     *  "id" = "\d+"
     * })
     * @Method("DELETE")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Skill $skill)
    {
        $form = $this->createDeleteForm($skill);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->get('skill.manager')->removeEntity($skill);
            $this->addFlash('danger', 'skill "' . $skill->getName() . '" removed');
        }

        return $this->redirectToRoute('admin_skills');
    }

    /**
     * Creates a form to delete a skill entity.
     *
     * @param \AppBundle\Entity\Skill $skill The skill entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Skill $skill)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_skill_delete', array('id' => $skill->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
