<?php

namespace AdminBundle\DataFixtures\ORM;

use AppBundle\Entity\CommentCode;
use AppBundle\Entity\CommentPost;
use AdminBundle\DataFixtures\ORM\CodeFixtures;
use AdminBundle\DataFixtures\ORM\PostFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of CodeFixtures
 *
 * @author linkus
 */
class CommentFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $cc = new CommentCode();
        $cc
                ->setContent('comment on php')
                ->setCode($this->getReference('code-php'))
                ->setUser($this->getReference('user-user'))
        ;
        $manager->persist($cc);
        
        $cc2 = new CommentCode();
        $cc2
                ->setContent('comment on javascript')
                ->setCode($this->getReference('code-javascript'))
                ->setUser($this->getReference('user-admin'))
        ;
        $manager->persist($cc2);
        
        
        $cp = new CommentPost();
        $cp
                ->setContent('comment on php')
                ->setPost($this->getReference('post-1'))
                ->setUser($this->getReference('user-user'))
        ;
        $manager->persist($cp);
        
        $cp2 = new CommentPost();
        $cp2
                ->setContent('comment on post 2')
                ->setPost($this->getReference('post-2'))
                ->setUser($this->getReference('user-admin'))
        ;
        $manager->persist($cp2);
        
        $manager->flush();
        
        

        $cp = new CommentPost();
    }
    
    public function getDependencies()
    {
        return array(
            CodeFixtures::class,
            PostFixtures::class,
        );
    }

}
