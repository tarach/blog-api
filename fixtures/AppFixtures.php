<?php

declare(strict_types=1);

namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $objects = $loader->loadFile(__DIR__ . '/fixtures.yml')->getObjects();

        foreach ($objects as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }
}
