<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of ThemeFixtures
 *
 * @author linkus
 */
class ThemeFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $data = [
            'ambiance',
            'chaos',
            'chrome',
            'clouds',
            'clouds_midnight',
            'cobalt',
            'crimson_editor',
            'dawn',
            'dreamweaver',
            'eclipse',
            'github',
            'gob',
            'gruvbox',
            'idle_fingers',
            'iplastic',
            'katzenmilch',
            'kr_theme',
            'kuroir',
            'merbivore',
            'merbivore_soft',
            'mono_industrial',
            'monokai',
            'pastel_on_dark',
            'solarized_dark',
            'solarized_light',
            'sqlserver',
            'terminal',
            'textmate',
            'tomorrow',
            'tomorrow_night',
            'tomorrow_night_blue',
            'tomorrow_night_bright',
            'tomorrow_night_eighties',
            'twilight',
            'vibrant_ink',
            'xcode',
        ];
        foreach ($data as $name) {
            $theme = new Theme;
            $theme->setName($name);
            $manager->persist($theme);
        }

        $manager->flush();
    }

}
