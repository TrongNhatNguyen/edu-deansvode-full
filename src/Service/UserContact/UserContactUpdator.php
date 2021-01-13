<?php

namespace App\Service\UserContact;

class UserContactUpdator
{
    private $userContactFetcher;
    
    public function __construct(UserContactFetcher $userContactFetcher)
    {
        $this->userContactFetcher = $userContactFetcher;
    }


    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $userContact = $this->userContactFetcher->getUserContactById($data['id']);

        foreach ($data as $field => $value) {
            if ($field === 'id') {
                continue;
            }

            $setter = 'set' . $field;

            if (!method_exists($userContact, $setter)) {
                continue;
            }

            $userContact->$setter($value);
        }

        return $userContact;
    }
}
