<?php

namespace AppBundle\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Model Repository
 *
 * @author Linkus
 *
 * Common custom repository methods.
 */
abstract class RepositoryModel extends EntityRepository
{
    protected $icPa = 0;

    /**
     *
     * criteria example:
     * <code>$criteria = [
     *  'filedname' => 0,
     *  'fieldname' => 'aaa',
     *  'eq' => ['fieldname', 1],
     *  'eq_1' => ['fieldname2', 1],
     *  'isNull' => ['fieldname'],
     *  'in' =>  ['fieldname' => [value1, value2, value3]],
     *  'between' => ['fieldname', value-min, value-max]
     * ]</code>
     *
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param string $Alias
     *
     * @return QueryBuilder
     *
     */
    public function findByOption(array $criteria = null, array $orderBy = null, $limit = null, $offset = null, $Alias = 'z')
    {
        $qb = $this->createQueryBuilder($Alias);
        
        if ($criteria) {
            foreach ($criteria as $k => $v) {
                if (is_array($v)) {
                    $this->addCriteria($qb, $Alias, $k, $v);
                } else {
                    $qb
                            ->setParameter(':' . $k, $v)
                            ->andWhere($qb->expr()->eq($Alias . '.' . $k, ':' . $k))
                    ;
                }
            }
        }
        if ($orderBy) {
            foreach ($orderBy as $order => $direction) {
                $qb->addOrderBy($Alias . '.' . $order, $direction);
            }
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb;
    }

    protected function addCriteria(QueryBuilder &$qb, $Alias, $k, array $v)
    {
        $this->icPa++;
        $kp = explode('_', $k);
        if (count($kp) == 2) {
            $k = $kp[0];
        }

        $comp1 = ['eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn'];
        $comp2 = ['isNull', 'isNotNull'];
        $comp3 = ['like', 'notLike'];
        if (in_array($k, $comp1)) {
            if (count($v) == 2) {
                //                echo '[ok2]';
                $qb->setParameter(':' . $v[0] . $this->icPa, $v[1]);
                $qb->andWhere($qb->expr()->$k($Alias . '.' . $v[0], ':' . $v[0] . $this->icPa));
            } elseif (count($v) == 3) {
                //                echo '[ok3]';
                $qb->andWhere($qb->expr()->$k($Alias . '.' . $v[0], $Alias . '.' . $v[1]));
            }
        } elseif (in_array($k, $comp2)) {
            $qb->andWhere($qb->expr()->$k($Alias . '.' . $v[0]));
        } elseif (in_array($k, $comp3)) {
            $qb->setParameter(':' . $v[0] . $this->icPa, '%' . $v[1] . '%');
            $qb->andWhere($qb->expr()->$k($Alias . '.' . $v[0], ':' . $v[0] . $this->icPa));
        } elseif ($k === 'between') {
            $qb->setParameter(':' . $k . $this->icPa . 'x', $v[1]);
            $qb->setParameter(':' . $k . $this->icPa . 'y', $v[2]);
            $qb->andWhere($qb->expr()->between($Alias . '.' . $v[0], ':' . $k . $this->icPa . 'x', ':' . $k . $this->icPa . 'y'));
        }
    }
    
    /**
     * <code>$set = [
     *      field1 => value,
     *      field2 => value
     * ]</code>
     *
     * @param QueryBuilder $qb
     * @param array $set
     * @param string $Alias
     *
     * @return void
     */
    public function updateSetBy(QueryBuilder $qb, array $set, $Alias = 'z')
    {
        $qb->update();
        foreach ($set as $field => $value) {
            $qb->setParameter(':'.$field, $value);
            $qb->set($Alias.'.'.$field, ':'.$field);
        }
        $qb->getQuery()->execute();
    }
}
