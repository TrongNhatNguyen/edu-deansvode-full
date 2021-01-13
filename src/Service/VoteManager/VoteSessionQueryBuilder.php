<?php

namespace App\Service\VoteManager;

use App\DTO\QueryObject\VoteSession\VoteSessionListQuery;
use App\Repository\VoteSessionRepository;

class VoteSessionQueryBuilder
{
    private $voteSessionRepository;

    public function __construct(VoteSessionRepository $voteSessionRepository)
    {
        $this->voteSessionRepository = $voteSessionRepository;
    }


    public function getAllVoteSessionQuery()
    {
        $queryBuilder = $this->voteSessionRepository->createQueryBuilder('v')
        ->select()
        ->orderBy('v.beginAt', 'DESC');

        return $queryBuilder;
    }

    public function getOpeningVoteSessionQuery()
    {
        $queryBulder = $this->voteSessionRepository->createQueryBuilder('v')
        ->select()
        ->where('v.closedAt is null');

        return $queryBulder;
    }

    public function buildVoteSessionListQuery(array $request)
    {
        $voteSessionListQuery = new VoteSessionListQuery();

        foreach ($request as $key => $value) {
            if ($key === "status") {
                $voteSessionListQuery->conditions[$key] = $value;
            }
        }

        return $voteSessionListQuery;
    }

    public function getVoteSessionByListQuery($listQuery)
    {
        $queryBuilder = $this->voteSessionRepository->createQueryBuilder('v')
        ->select()
        ->addOrderBy('v.id', 'DESC');

        if (!empty($listQuery->conditions)) {
            foreach ($listQuery->conditions as $key => $value) {
                if ($value == null) {
                    $key = null;
                }

                switch ($key) {
                    case 'status':
                        $queryBuilder->andWhere('v.status = :status_val')
                        ->setParameter('status_val', $value);
                        break;
                    
                    default:
                        break;
                }
            }
        }

        return $queryBuilder;
    }
}
