<?php

namespace App\Service\UserContact;

use App\Repository\UserContactRepository;

class UserContactFetcher
{
    private $userContactRepository;

    public function __construct(UserContactRepository $userContactRepository)
    {
        $this->userContactRepository = $userContactRepository;
    }


    public function getUserContactById(int $id)
    {
        return $this->userContactRepository->find($id);
    }
}
