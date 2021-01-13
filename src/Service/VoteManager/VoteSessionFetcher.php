<?php

namespace App\Service\VoteManager;

use App\DTO\QueryObject\VoteSession\VoteSessionListQuery;
use App\Repository\VoteSessionRepository;

class VoteSessionFetcher
{
    private $voteSessionRepository;
    private $voteSessionQueryBuilder;

    public function __construct(
        VoteSessionRepository $voteSessionRepository,
        VoteSessionQueryBuilder $voteSessionQueryBuilder
    ) {
        $this->voteSessionRepository = $voteSessionRepository;
        $this->voteSessionQueryBuilder = $voteSessionQueryBuilder;
    }


    public function getVoteSessionById(int $id)
    {
        return $this->voteSessionRepository->find($id);
    }

    public function getVoteSessionByYear($year)
    {
        return $this->voteSessionRepository->findOneBy(['year' => $year]);
    }

    public function listAllVoteSession()
    {
        $queryBuilder = $this->voteSessionQueryBuilder->getAllVoteSessionQuery();

        return $queryBuilder->getQuery()->getResult();
    }

    public function openingVoteSession()
    {
        $queryBuilder = $this->voteSessionQueryBuilder->getOpeningVoteSessionQuery();

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
