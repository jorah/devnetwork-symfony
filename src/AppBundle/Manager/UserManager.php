<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager as Om;
use AppBundle\Model\ManagerModel;

/**
 * Description of UserManager
 *
 * @author linkus
 */
class UserManager extends ManagerModel
{
    protected $om;

    public function __construct(OM $objectManager)
    {
        $this->om = $objectManager;
    }

    public function findUsers($isAdmin = false)
    {
        
    }

}
