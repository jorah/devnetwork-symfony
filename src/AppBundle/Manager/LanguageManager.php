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
    protected $paginator;
    
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository(Language::class);
    }
    
    public function findLanguages($page)
    {
        $criteria = [];
        $qb = $this->repository->findLanguages($criteria);
        return $this->paginator->paginate($qb, $page, 20, ['distinct' => false]);
    }
}