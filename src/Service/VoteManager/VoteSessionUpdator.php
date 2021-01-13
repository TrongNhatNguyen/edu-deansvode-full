<?php

namespace App\Service\VoteManager;

class VoteSessionUpdator
{
    private $voteSessionFetcher;

    public function __construct(VoteSessionFetcher $voteSessionFetcher)
    {
        $this->voteSessionFetcher = $voteSessionFetcher;
    }


    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $voteSession = $this->voteSessionFetcher->getVoteSessionById($data['id']);

        foreach ($data as $field => $value) {
            if ($field === 'id') {
                continue;
            }

            $setter = 'set' . $field;

            if (!method_exists($voteSession, $setter)) {
                continue;
            }

            $voteSession->$setter($value);
        }

        return $voteSession;
    }
}
