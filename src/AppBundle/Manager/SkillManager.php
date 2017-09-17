<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\Skill;

/**
 * Description of SkillManager
 *
 * @author linkus
 */
class SkillManager extends ManagerModel
{
    protected $om;
    protected $class_entity;
    protected $paginator;
    protected $repository;
    protected $skill_path;

    public function __construct(Om $objectManager, Knp $paginator, $path)
    {
        $this->om = $objectManager;
        $this->class_entity = Skill::class;
        $this->skill_path = $path;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository($this->class_entity);
    }

    /**
     * Find skills entites
     * 
     * @param int $page
     * @param array $criteria
     * 
     * @return ArrayCollection entities paginated
     */
    public function findSkills(array $criteria = [])
    {
        return $this->repository->findSkills($criteria);
    }

    /**
     * Remove a skill entity with image
     * 
     * @param Skill $skill
     */
    public function removeEntity(Skill $skill)
    {
        if (!empty($skill->getImage())) {
            $path = $this->skill_path . '/' . $skill->getImage();
            if(true === file_exists($path)){
                unlink($path);
            }
        }
        $this->deleteEntity($skill);
    }

}
