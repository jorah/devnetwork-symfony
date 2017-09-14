<?php

namespace AdminBundle\DataFixtures\ORM;

use AppBundle\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of CodeFixtures
 *
 * @author linkus
 */
class TagFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setCount(0)->setName('hello-world');
        $manager->persist($tag);
        
        $tag2 = new Tag();
        $tag2->setCount(0)->setName('blabla');
        $manager->persist($tag2);
        
        $manager->flush();
        $this->addReference('tag-hello', $tag);
        $this->addReference('tag-blabla', $tag2);
    }

}
