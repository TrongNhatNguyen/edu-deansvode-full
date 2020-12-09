<?php

namespace App\Service;

use App\Repository\VoteSessionRepository;

class VoteSessionService
{
    private $voteSessionRepository;

    public function __construct(VoteSessionRepository $voteSessionRepository)
    {
        $this->voteSessionRepository = $voteSessionRepository;
    }

    public function getLatestSessionYears(int $limit)
    {
        $queryBuilder = $this->voteSessionRepository->createQueryBuilder('v');
        $queryBuilder
            ->select()
            ->setMaxResults($limit)
            ->orderBy('v.year', 'DESC')
            ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function getVoteSessionByStatus($year = null, $status = 1)
    {
        try {
            $queryBuilder = $this->voteSessionRepository->createQueryBuilder('v');

            $queryBuilder->select()->where('v.status=:status_val')
            ->setParameter('status_val', $status);
            if ($year) {
                $queryBuilder->andWhere('v.year=:year_val')
                ->setParameter('year_val', $year);
            }
            $queryBuilder->orderBy('v.id', 'ASC');
            return $queryBuilder->getQuery()->getResult();
        } catch (\Throwable $th) {
            return [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
        }
    }
}
