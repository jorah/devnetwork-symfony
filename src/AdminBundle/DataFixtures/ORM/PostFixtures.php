<?php

namespace AdminBundle\DataFixtures\ORM;

use AppBundle\Entity\Post;
use AdminBundle\DataFixtures\ORM\UserFixtures;
use AdminBundle\DataFixtures\ORM\LanguageFixtures;
use AdminBundle\DataFixtures\ORM\TagFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of CodeFixtures
 *
 * @author linkus
 */
class PostFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $p1 = new Post();
        $p1
                ->setTitle('My first post')
                ->setContent('content of the first post')
                ->setUser($this->getReference('user-admin'))
                ->addTag($this->getReference('tag-blabla'))
        ;
        $manager->persist($p1);
        $p2 = new Post();
        $p2
                ->setTitle('My 2nd post')
                ->setContent('content of the first post')
                ->setUser($this->getReference('user-user'))
                ->addTag($this->getReference('tag-blabla'))
        ;
        $manager->persist($p2);
        $manager->flush();
        $this->addReference('post-1', $p1);
        $this->addReference('post-2', $p2);
        
        $em = $this->container->get('doctrine')->getManager();
        $manu = $em->getRepository('AppBundle:User')->findOneByUsername('manu');
        $manu2 = $em->getRepository('AppBundle:User')->findOneByUsername('manu2');
        $manu->addFavPost($p2);
        $manu2->addFavPost($p1);
        
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            LanguageFixtures::class,
            TagFixtures::class
        );
    }

}
