<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use Knp\Component\Pager\Paginator as Knp;
use AppBundle\Model\ManagerModel;
use AppBundle\Entity\Code;
use AppBundle\Entity\Language;

/**
 * Description of CodeManager
 *
 * @author linkus
 */
class CodeManager extends ManagerModel
{
    protected $om;
    protected $class_entity;
    protected $paginator;
    protected $repository;

    public function __construct(Om $objectManager, Knp $paginator)
    {
        $this->om = $objectManager;
        $this->class_entity = Code::class;
        $this->paginator = $paginator;
        $this->repository = $this->om->getRepository($this->class_entity);
    }

    /**
     * Find code entites by criteria
     * 
     * @param int $page
     * @param array $criteria
     * 
     * @return ArrayCollection entities paginated
     */
    public function findCodes($page = 1, array $criteria = [])
    {
        $qb = $this->repository->findCodes($criteria);

        $entities = $this->paginator->paginate($qb, $page, 20, ['distinct' => true]);

        return $entities;
    }

    /**
     * Get a code entity with related entities
     * 
     * @param int $id
     * 
     * @return null|Code
     */
    public function showCode($id)
    {
        return $this->repository->findShow($id);
    }

    /**
     * Check if code entities with a specific language exist
     * 
     * @param mixed $language id, name or entity
     * 
     * @return boolean
     */
    public function hasLanguage($language)
    {
        if (is_a($language, Language::class)) {
            $language = $language->getId();
        }
        return $this->repository->hasLanguage($language);
    }
    
    /**
     * Delete a code entity
     * 
     * @param Code $code
     * 
     * @param string $type
     */
    public function deleteCode(Code $code, $type)
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
