<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\Theme;

/**
 * Description of ThemeManager
 *
 * @author linkus
 */
class ThemeManager extends ManagerModel
{
    protected $om;
    protected $paginator;
    protected $class_entity;
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->class_entity = Theme::class;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository($this->class_entity);
    }
    
    public function findThemes()
    {
        return $this->repository->findBy([], ['name' => 'ASC']);
    }
    
    
    
}
