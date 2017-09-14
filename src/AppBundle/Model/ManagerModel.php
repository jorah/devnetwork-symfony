<?php

namespace AppBundle\Model;

/**
 * Model Manager
 *
 * Common method used in Manager class
 *
 * @version 1.2
 * @link http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/working-with-objects.html Working with Objects (Persist, Remove,Detach)
 * @link http://www.doctrine-project.org/api/orm/2.5/class-Doctrine.ORM.UnitOfWork.html UnitOfWork API
 * @link http://www.doctrine-project.org/api/orm/2.5/class-Doctrine.ORM.EntityManager.html EntityManager API
 * @author Linkus
 */
abstract class ManagerModel
{

    /**
     * @var string alias of an entity
     */
    protected $alias;

    #Tool

    /**
     * Return alias from an entity name
     *
     * @return string
     */
    public function getAlias()
    {
        if (!$this->alias) {
            $class = explode('\\', $this->class_entity);
            $this->alias = strtolower(substr(end($class), 0, 1));
        }
        return $this->alias;
    }

    /**
     * Get fields names of an entity
     *
     * @return array
     */
    public function getColumnNames()
    {
        return $this->om->getClassMetadata($this->class_entity)->getColumnNames();
    }

    /**
     * Get fields names of an entity with type
     *
     * @return array
     */
    public function getFieldsInfo()
    {
        $fields = $this->getColumnNames();
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $this->om->getClassMetadata($this->class_entity)->getTypeOfField($field);
        }
        return $data;
    }

    /**
     * return class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class_entity;
    }

    #Read Action

    /**
     * return unique result by options
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * return unique result by id
     *
     * @param int $id
     *
     * @return object
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * return a collection of entities by options
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     *
     * @return ArrayCollection
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit);
    }

    /**
     * return a QueryBuilder with options
     *
     *
     * criteria example:
     * <pre><code>$criteria = [
     *  'filedname' => 0,
     *  'fieldname' => 'aaa',
     *  'eq' => ['fieldname', 1],
     *  'eq_1' => ['fieldname2', 1],
     *  'isNull' => ['fieldname'],
     *  'in' =>  ['fieldname' => [value1, value2, value3]],
     *  'between' => ['fieldname', value-min, value-max]
     * ]</code></pre>
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     *
     * @return QueryBuilder
     */
    public function findByOption(array $criteria = null, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findByOption($criteria, $orderBy, $limit, $offset, $this->getAlias());
    }

    /**
     * Custom findby made for KnnpPaginatorBundle
     * So without order, without limit
     *
     * @param array $criteria
     *
     * @return array [QueryBuilder, DQL]
     */
    public function findPaginate(array $criteria = null, array $orderBy = null)
    {
        $qb = $this->repository->findByOption($criteria, $orderBy, null, null, $this->getAlias());
        return [
            'qb' => $qb,
            'dql' => $qb->getDQL(),
        ];
    }

    /**
     * process entries by iterating
     *
     * <code>foreach ($iterableResult as $row) {
     *     $entity = $row[0];
     * }</code>
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     *
     * @return query result:

     */
    public function iterateBy(array $criteria = null, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->findByOption($criteria, $orderBy, $limit, $offset, $this->getAlias());

        return $qb->getQuery()->iterate();
    }

    /**
     * COUNT BY query
     *
     * @param array $criteria
     *
     * @return int
     */
    public function countBy(array $criteria = null)
    {
        $qb = $this->findByOption($criteria, null, null, null, $this->getAlias());
        $qb->select('COUNT(' . $this->getAlias() . ')');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * UPDATE BY query
     *
     *
     * set example:
     * <code>$set = [
     *      field1 => value,
     *      field2 => value
     * ]</code>
     *
     * @param array $set
     * @param array $criteria
     * @param int $limit
     *
     * @return void
     */
    public function updateBy(array $set, array $criteria = null, $limit = null)
    {
        $qb = $this->findByOption($criteria, null, $limit, null, $this->getAlias());
        $this->repository->updateSetBy($qb, $set, $this->getAlias());
    }

    /**
     * DELETE query
     *
     * @param array $criteria
     * @param int $limit
     *
     * @return void
     */
    public function deleteBy(array $criteria = null, $limit = null)
    {
        $qb = $this->findByOption($criteria, null, $limit, null, $this->getAlias());
        $qb->delete();
        $qb->getQuery()->execute();
    }

    #Persist/Flush

    /**
     * Persist an entity
     *
     * @param Entity $entity
     *
     * @return self
     */
    public function persist($entity)
    {
        $this->om->persist($entity);
        return $this;
    }

    /**
     * Deatach an entity
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function detach($entity)
    {
        $this->om->detach($entity);
    }

    /**
     * Synchronizes objects with database
     *
     * @param null|object|array $entity
     *
     * @return self
     */
    public function flush($entity = null)
    {
        $this->om->flush($entity);
        return $this;
    }

    /**
     * empty entitymanager
     */
    public function clear()
    {
        $this->om->clear();
    }

    /**
     * Remove an entity
     *
     * @param object|int $entity entity or entity id
     * @param bool $andFlush
     *
     * @return bool
     */
    public function deleteEntity($entity, $andFlush = true)
    {
        if (is_numeric($entity)) {
            $entity = $this->find($entity);
            if (!$entity) {
                return false;
            }
        }

        $this->om->remove($entity);
        if ($andFlush) {
            $this->om->flush();
        }
        return true;
    }
    
    /**
     * Check if an entity in the entity manager
     * is flagged for update (flush)
     *
     * @param object $entity
     *
     * @return bool
     */
    public function isEntityModified($entity)
    {
        $uow = $this->om->getUnitOfWork();
        $uow->computeChangeSets();

        return $uow->isEntityScheduled($entity) ? true : false;
    }
    
    /**
     * Truncate table on MYSQL databases
     */
    public function truncate()
    {
        $cmd = $this->om->getClassMetadata($this->class_entity);
        $connection = $this->om->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        $connection->executeUpdate($q);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
