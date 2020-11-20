<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $objectManager)
    {
        $sqlFolder = __DIR__ . '/sql/';

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

            print "Imported file: " . $sqlFile . PHP_EOL;
        }
    }
}
