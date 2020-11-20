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
        $sqlFolder = __DIR__ . '/Sql/';

        $sqlFiles = scandir($sqlFolder);

        foreach ($sqlFiles as $sqlFile) {
            if (!strpos($sqlFile, '.sql')) {
                continue;
            }

            $sqlContent = "SET FOREIGN_KEY_CHECKS = 0;";

            $sqlContent .= file_get_contents($sqlFolder . $sqlFile);

            $sqlContent .= "SET FOREIGN_KEY_CHECKS = 1;";

            print "Importing: " . $sqlFile . PHP_EOL;

            $objectManager->getConnection()->exec($sqlContent);
            $objectManager->flush();

            print "File: " . $sqlFile . PHP_EOL;
        }
    }
}
