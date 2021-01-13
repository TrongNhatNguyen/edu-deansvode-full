<?php

namespace App\Service\VoteManager;

use App\Entity\VoteSession;

class VoteSessionCreator
{
    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $voteSession = new VoteSession();

        foreach ($data as $field => $value) {
            $setter = 'set' . $field;

            if (!method_exists($voteSession, $setter)) {
                continue;
            }

            $voteSession->$setter($value);
        }

        return $voteSession;
    }
}
