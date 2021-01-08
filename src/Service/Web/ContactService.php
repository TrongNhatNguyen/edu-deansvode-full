<?php

namespace App\Service\Web;

use App\Entity\UserContact;
use App\Repository\UserContactRepository;

class ContactService
{
    private $userContactRepository;

    public function __construct(UserContactRepository $userContactRepository)
    {
        $this->userContactRepository = $userContactRepository;
    }

    public function createUserContact($sendEmailRequest)
    {
        try {
            $userContact = new UserContact();

            $userContact->setFullName($sendEmailRequest->fullName);
            $userContact->setEmail($sendEmailRequest->email);
            $userContact->setInstitution($sendEmailRequest->institution);
            $userContact->setPosition($sendEmailRequest->position);
            $userContact->setMessage($sendEmailRequest->message);
            $userContact->setStatus($sendEmailRequest->status);
            $userContact->setCreatedAt(new \DateTime('now'));
            $userContact->setUpdatedAt(new \DateTime('now'));
            $userContact->setReceiver($sendEmailRequest->receiver);

            $this->userContactRepository->fetching($userContact);

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

    public function updateUserContact($id)
    {
        try {
            $userContact = $this->userContactRepository->find((int) $id);

            $userContact->setStatus(1);
            $userContact->setUpdatedAt(new \DateTime('now'));

            $this->userContactRepository->fetching($userContact);

            return exit;
        } catch (\Exception $ex) {
            return ['error' => [get_class($ex) => $ex->getMessage()]];
        }
    }
}
