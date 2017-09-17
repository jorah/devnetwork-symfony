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

    /**
     * Get a user entity with related entities
     * 
     * @param int $id
     * 
     * @return null|User
     */
    public function showUser($id)
    {
        return $this->repository->findShow($id);
    }
    
    /**
     * Promote/demode Admin role
     * 
     * @param User $user
     * @param string $role
     */
    public function promoteUser(User $user, $role)
    {
        if ($role == 'admin') {
            $user->addRole('ROLE_ADMIN');
        } elseif ($role == 'user') {
            $user->removeRole('ROLE_ADMIN');
        }
        $this->om->flush();
    }
    
    public function deleteCode(User $user, $type)
    {
        if ($type == 'warning') {
            $user->setStatus(1);
            $this->om->flush();
        } elseif ($type == 'info') {
            $user->setStatus(0);
            $this->om->flush();
        } elseif ($type == 'danger') {
            $this->deleteEntity($user);
        }
    }

}
