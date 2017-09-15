<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\CommentPost;

/**
 * Description of CommentPostManager
 *
 * @author linkus
 */
class CommentPostManager extends ManagerModel
{
    protected $om;
    protected $paginator;
    
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository(CommentPost::class);
    }
}