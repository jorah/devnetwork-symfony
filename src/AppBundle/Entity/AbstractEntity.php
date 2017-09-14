<?php

namespace AppBundle\Entity;

/**
 * Description of AbstractEntity
 *
 * @author linkus
 */
abstract class AbstractEntity
{
    /**
     * prePersist
     * method: add current date on insertion
     * 
     * @return $this
     */
    public function insertDate()
    {
        $this->createdAt = new \DateTime();
        
        return $this;
    }
    
    /**
     * preUpdate
     * method: add current date on update
     * 
     * @return $this
     */
    public function updateDate()
    {
        $this->updatedAt = new \DateTime();
        
        return $this;
    }
    
    /** oui/non? */
    public function updateCountTag()
    {
        foreach ($this->tags as $tag){
            $tag->setCount($tag->getCount() + 1);
        }
    }
}
