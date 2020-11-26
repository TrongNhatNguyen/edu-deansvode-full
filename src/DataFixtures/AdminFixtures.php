<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $objectManager)
    {
        $credentials = [
            [
                'username' => 'silkwires',
                'password' => 'silk@455',
                'roles' => ["ROLE_SUPER_ADMIN"],
                'email' => 'info@silkwires.com',
                'status' => Admin::ACTIVE,
            ],
            [
                'username' => 'SMBG',
                'password' => 'edu@455',
                'roles' => ["ROLE_SUPER_ADMIN"],
                'email' => 'deans.vote@eduniversal.com',
                'status' => Admin::ACTIVE,
            ],
            [
                'username' => 'mariam.akkar',
                'password' => 'edu@455',
                'roles' => ["ROLE_SUPER_ADMIN"],
                'email' => '',
                'status' => Admin::ACTIVE,
            ],
        ];

        foreach ($credentials as $credential) {
            $admin = new Admin();

            foreach ($credential as $key => $value) {
                $setter = 'set' . $key;

                if (!method_exists($admin, $setter)) {
                    continue;
                }

                if ($key === 'password' && !empty($value)) {
                    $value = $this->passwordEncoder->encodePassword($admin, $value);
                }

                $admin->$setter($value);
            }

            $objectManager->persist($admin);
        }

        $objectManager->flush();
    }
}
