<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
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
    protected $paginator;
    
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository(User::class);
    }

    public function findUsers($page, $isAdmin = false)
    {
        $criteria = [];
        $qb = $this->repository->findUsers($criteria, $isAdmin);
        return $this->paginator->paginate($qb, $page, 20, ['distinct' => true]);
    }

}
