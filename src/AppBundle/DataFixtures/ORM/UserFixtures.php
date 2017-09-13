<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of UserFixtures
 *
 * @author linkus
 */
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('manu');
        $user1->setUsernameCanonical('manu');
        $user1->setEmail('aa@aa.aa');
        $user1->setEmailCanonical('aa@aa.aa');
        $user1->setEnabled(true);
        $user1->setPassword('$2y$13$5bgouT3lwOKaG3tVrohqc.Wcj92gz7jZfDYm5LaII4ogfiEZc0nkq');
        $user1->addRole('ROLE_ADMIN');
    }
}
