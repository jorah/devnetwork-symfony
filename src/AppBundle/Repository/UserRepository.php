<?php

namespace AppBundle\Repository;

use AppBundle\Model\RepositoryModel;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends RepositoryModel
{

    /**
     * Find users entities filtered by criteria
     * 
     * @param array $criteria
     * 
     * @return QueryBuilder
     */
    public function findUsers(array $criteria, $isAdmin)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->leftJoin('u.skills', 's')->addSelect('s');

        if (false === $isAdmin) {
            $qb->andWhere($qb->expr()->isNull('u.status'));
        }
        return $qb;
    }

    /**
     * Get an user entity with related entities
     * 
     * @param int $id
     * 
     * @return null|User
     */
    public function findShow($id)
    {
        $qb = $this->createQueryBuilder('u');
        $qb
                ->leftJoin('u.skills', 's')->addSelect('s')
                ->where($qb->expr()->eq('u.id', ':id'))
                ->setParameter('id', $id)
        ;
        return $qb->getQuery()->getOneOrNullResult();
    }

}
