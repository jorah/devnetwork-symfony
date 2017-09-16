<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\Post;

/**
 * Description of PostManager
 *
 * @author linkus
 */
class PostManager extends ManagerModel
{
    protected $om;
    protected $class_entity;
    protected $paginator;
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->class_entity = Post::class;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository($this->class_entity);
    }

    /**
     * Find post entites by criteria
     * 
     * @param int $page
     * @param array $criteria
     * 
     * @return ArrayCollection entities paginated
     */
    public function findPosts($page = 1, array $criteria = [])
    {
        $qb = $this->repository->findPosts($criteria);

        $entities = $this->paginator->paginate($qb, $page, 20, ['distinct' => true]);

        return $entities;
    }

    /**
     * Get a post entity with related entities
     * 
     * @param int $id
     * 
     * @return Post
     */
    public function showPost($id)
    {
        return $this->repository->findShow($id);
    }
    
    /**
     * Delete a post entity
     * 
     * @param Post $code
     * 
     * @param string $type
     */
    public function deleteCode(Post $code, $type)
    {
        if ($type == 'warning') {
            $code->setStatus(1);
            $this->om->flush();
        } elseif ($type == 'info') {
            $code->setStatus(0);
            $this->om->flush();
        } elseif ($type == 'danger') {
            $this->deleteEntity($code);
        }
    }

}
