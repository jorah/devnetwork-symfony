<?php

namespace AdminBundle\DataFixtures\ORM;

use AppBundle\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of CodeFixtures
 *
 * @author linkus
 */
class SkillFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $skill = new Skill();
        $skill->setName('symfony');
        $manager->persist($skill);
        
        $skill2 = new Skill();
        $skill2->setName('node.js');
        $manager->persist($skill2);
        
        $skill3 = new Skill();
        $skill3->setName('mongo-db');
        $manager->persist($skill3);
        
        $skill4 = new Skill();
        $skill4->setName('angular.js');
        $manager->persist($skill4);
        
        $manager->flush();
        $this->addReference('skill-symfony', $skill);
        $this->addReference('skill-node', $skill2);
        $this->addReference('skill-mongo', $skill3);
        $this->addReference('skill-angular', $skill4);
    }

}
