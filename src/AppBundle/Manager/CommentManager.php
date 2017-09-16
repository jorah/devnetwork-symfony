<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\CommentCode;
use AppBundle\Entity\CommentPost;

/**
 * Description of CommentPostManager
 *
 * @author linkus
 */
class CommentManager extends ManagerModel
{
    protected $om;
    protected $class_entity;
    protected $paginator;
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->paginator = $paginator;
    }

    /**
     * Initilaize manager with CommentCode/CommentPost class
     * 
     * @param string $type
     * @return $this
     * 
     * @throws Exception
     */
    public function set($type)
    {
        if ($type == 'code') {
            $this->class_entity = CommentCode::class;
        } elseif ($type = 'post') {
            $this->class_entity = CommentPost::class;
        } else {
            throw new Exception('CommentManager: bad initialization');
        }
        $this->repository = $this->om->getRepository($this->class_entity);

        return $this;
    }

    /**
     * Find comments entites by criteria
     * 
     * @param int $page
     * @param array $criteria
     * 
     * @return ArrayCollection entities paginated
     */
    public function findComments($page = 1, array $criteria = [])
    {
        $qb = $this->repository->findComments($criteria);

        $entities = $this->paginator->paginate($qb, $page, 20, ['distinct' => true]);

        return $entities;
    }

    public function deleteComment($id)
    {
        $comment = $this->repository->find($id);
        if(!$comment){
            return false;
        }
        $this->deleteEntity($comment);
        return true;
    }

}
