<?php

namespace App\Service\Web;

use App\Entity\UserContact;
use App\Repository\UserContactRepository;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    private $userContactRepository;
    private $entityManager;

    public function __construct(UserContactRepository $userContactRepository, EntityManagerInterface $entityManager)
    {
        $this->userContactRepository = $userContactRepository;
        $this->entityManager = $entityManager;
    }

    public function createUserContact($data)
    {
        try {
            $userContact = new UserContact();

            $userContact->setFullName($data['full_name']);
            $userContact->setEmail($data['email']);
            $userContact->setInstitution($data['institution']);
            $userContact->setPosition($data['position']);
            $userContact->setMessage($data['message']);
            $userContact->setStatus($data['status']);
            $userContact->setCreatedAt(new \DateTime('now'));
            $userContact->setUpdatedAt(new \DateTime('now'));
            $userContact->setReceiver($data['receiver']);

            $this->entityManager->persist($userContact);
            $this->entityManager->flush();

            return [
                'status' => 'success',
                'id' => $userContact->getId(),
                'message' => 'your idear successfully sent!',
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'message' => 'failed create user contact',
                'error' => [get_class($ex) => $ex->getMessage()]
            ];
        }
    }

    public function updateUserContact(int $id)
    {
        try {
            $userContact = $this->userContactRepository->find((int) $id);

            $userContact->setStatus(1);

            $this->entityManager->persist($userContact);
            $this->entityManager->flush();

            return exit;
        } catch (\Exception $ex) {
            return ['error' => [get_class($ex) => $ex->getMessage()]];
        }
    }
}
