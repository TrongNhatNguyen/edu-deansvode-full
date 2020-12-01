<?php

namespace App\Security\Dean;

use App\Entity\Dean;
use App\Repository\DeanRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdatePassword
{
    private $deanRepository;
    private $entityManager;

    public function __construct(DeanRepository $deanRepository, EntityManagerInterface $entityManager)
    {
        $this->deanRepository = $deanRepository;
        $this->entityManager = $entityManager;
    }

    public function updatePassword($email)
    {
        try {
            $getUser = $this->deanRepository->findOneBy(['email1' => $email]);

            if (!$getUser) {
                return [
                    'status' => 'failed',
                    'error' => 'This account is not registered.',
                ];
            }

            $pass_random = $this->generateRandomPassword(6);

            $this->deanRepository->changePassword($getUser, $pass_random);

            return [
                'status' => 'success',
                'message' => 'successfully!',
                'dataUser' => ['firstName' => $getUser->getFirstName(), 'email1' => $getUser->getEmail1(), 'password1' => $pass_random]
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    protected function generateRandomPassword($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
