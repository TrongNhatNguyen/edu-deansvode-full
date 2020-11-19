<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;

class AppFixtures extends Fixture
{
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $objectManager)
    {
        $finder = new Finder();
        $finder->in(__DIR__, '/DataFixtures/sql');
        $finder->name('country.sql');

        foreach ($finder as $file) {
            $content = $file->getContents();

            $stmt = $this->container->get('doctrine.orm.entity_manager')->getConnection()->prepare($content);
            $stmt->execute();
        }
    }
}
