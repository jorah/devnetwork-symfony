<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\Tag;

/**
 * Description of TagManager
 *
 * @author linkus
 */
class TagManager extends ManagerModel
{
    protected $om;
    protected $paginator;
    
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository(Tag::class);
    }
    
    /**
     * Find tag entites by criteria
     * 
     * @param int $page
     * @param array $criteria
     * 
     * @return ArrayCollection entities paginated
     */
    public function findTags($page = 1, array $criteria = [])
    {
        $qb = $this->repository->findTags($criteria);

        $entities = $this->paginator->paginate($qb, $page, 20, ['distinct' => false]);

        return $entities;
    }
}