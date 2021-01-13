<?php

namespace App\Service\UserContact;

use App\Entity\UserContact;

class UserContactCreator
{
    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $userContact = new UserContact();

        foreach ($data as $field => $value) {
            $setter = 'set' . $field;

            if (!method_exists($userContact, $setter)) {
                continue;
            }

            $userContact->$setter($value);
        }

        return $userContact;
    }
}
