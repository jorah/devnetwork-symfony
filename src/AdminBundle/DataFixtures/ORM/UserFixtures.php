<?php

namespace AdminBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use AdminBundle\DataFixtures\ORM\SkillFixtures;
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
        $user1->addSkill($this->getReference('skill-symfony'));
        $user1->addSkill($this->getReference('skill-node'));
        $manager->persist($user1);
        
        $user2 = new User();
        $user2->setUsername('manu2');
        $user2->setUsernameCanonical('manu2');
        $user2->setEmail('ab@aa.aa');
        $user2->setEmailCanonical('ab@aa.aa');
        $user2->setEnabled(true);
        $user2->setPassword('$2y$13$5bgouT3lwOKaG3tVrohqc.Wcj92gz7jZfDYm5LaII4ogfiEZc0nkq');
        $user2->addRole('ROLE_USER');
        $user2->addSkill($this->getReference('skill-mongo'));
        $user2->addSkill($this->getReference('skill-angular'));
        $manager->persist($user2);

        $manager->flush();
        $this->addReference('user-admin', $user1);
        $this->addReference('user-user', $user2);
        
    }
    
    public function getDependencies()
    {
        return array(
            SkillFixtures::class,
        );
    }
}
