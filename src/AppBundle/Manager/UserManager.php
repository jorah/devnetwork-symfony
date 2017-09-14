<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\User;

/**
 * Description of UserManager
 *
 * @author linkus
 */
class UserManager extends ManagerModel
{
    protected $om;
    
    protected $repository;

    public function __construct(OM $objectManager)
    {
        $this->om = $objectManager;
        $this->repository = $this->om->getRepository(User::class);
    }

    public function findUsers($isAdmin = false)
    {
        $criteria = [];
        $qb = $this->repository->findUsers($criteria, $isAdmin);
        return $qb;
    }

}
