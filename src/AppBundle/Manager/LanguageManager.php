<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\Language;

/**
 * Description of LanguageManager
 *
 * @author linkus
 */
class LanguageManager extends ManagerModel
{
    protected $om;
    protected $class_entity;
    protected $paginator;
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->class_entity = Language::class;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository($this->class_entity);
    }

    /**
     * Get all programming languages with the numbers of code
     * ordered by name
     * 
     * @return array
     */
    public function findLanguagesByStat()
    {
        return $this->repository->getLanguagesStat();
    }

    /**
     * Remove a Language entity
     * 
     * @param Language $entity
     */
    public function removeEntity(Language $entity)
    {
        $this->deleteEntity($entity, true);
    }
    
    public function showOption($selected = null)
    {       
        
        $options = $this->findByOption([])->getQuery()->getArrayResult();
        $data = '<option></option>';
        foreach ($options as $key => $option){
            $data .= '<option value="'.$option['name'].'"'.($selected == $option['name'] ? ' selected' : null).'>'.$option['name'].'</option>';
        }
   



        return $data;
    }

}
