<?php

namespace AdminBundle\DataFixtures\ORM;

use AppBundle\Entity\Code;
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
class CodeFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $c1 = new Code();
        $c1
                ->setTitle('Hello world in php')
                ->setContent('<?php echo \'Hello world\'; !?>')
                ->setDescription('description - php')
                ->setLanguage($this->getReference('language-php'))
                ->setUser($this->getReference('user-admin'))
                ->addTag($this->getReference('tag-hello'))
        ;
        $manager->persist($c1);
        
        $c2 = new Code();
        $c2
                ->setTitle('Hello world in javascript')
                ->setContent('console.log("Hello world")')
                ->setDescription('description - javascript')
                ->setLanguage($this->getReference('language-javascript'))
                ->setUser($this->getReference('user-user'))
                ->addTag($this->getReference('tag-hello'))
        ;
        $manager->persist($c2);
        
        $manager->flush();
        
        $this->addReference('code-php', $c1);
        $this->addReference('code-javascript', $c2);
        
        $em = $this->container->get('doctrine')->getManager();
        $manu = $em->getRepository('AppBundle:User')->findOneByUsername('manu');
        $manu2 = $em->getRepository('AppBundle:User')->findOneByUsername('manu2');
        $manu->addFavCode($c2);
        $manu2->addFavCode($c1);
        
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
