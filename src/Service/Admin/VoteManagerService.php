<?php

namespace App\Service\Admin;

use App\DTO\QueryObject\VoteSession\VoteSessionListQuery;
use App\Entity\VoteSession;
use App\Repository\DeanRepository;
use App\Repository\VoteSessionRepository;

class VoteManagerService
{
    private $voteSessionRepository;
    private $deanRepository;

    public function __construct(
        VoteSessionRepository $voteSessionRepository,
        DeanRepository $deanRepository
    ) {
        $this->voteSessionRepository = $voteSessionRepository;
        $this->deanRepository = $deanRepository;
    }


    public function getAllVoteSession()
    {
        $queryBulder = $this->voteSessionRepository->createQueryBuilder('v')
        ->select()->orderBy('v.beginAt', 'DESC');

        return $queryBulder->getQuery()->getResult();
    }

    public function getOpeningVoteSession()
    {
        $queryBulder = $this->voteSessionRepository->createQueryBuilder('v')
        ->select()
        ->where('v.closedAt is null');

        return $queryBulder->getQuery()->getOneOrNullResult();
    }

    public function updateVoteSession($updateRequest)
    {
        try {
            $voteSession = $this->voteSessionRepository->find((int) $updateRequest->id);

            $voteSession->setClosedAt(new \DateTime('now'));
            $voteSession->setStatus($updateRequest->status);
            $voteSession->setUpdatedAt(new \DateTime('now'));

            $this->voteSessionRepository->fetching($voteSession);

            return [
                'status' => 'success',
                'message' => 'update successfully!'
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function createNewVoteSession($createRequest)
    {
        try {
            $voteSession = new VoteSession();

            $voteSession->setYear($createRequest->year);
            $voteSession->setBeginAt(new \DateTime('now'));
            $voteSession->setClosedAt(null);
            $voteSession->setStatus($createRequest->status);
            $voteSession->setCreatedAt(new \DateTime('now'));
            $voteSession->setUpdatedAt(new \DateTime('now'));

            $this->voteSessionRepository->fetching($voteSession);

            return [
                'status' => 'success',
                'message' => 'create successfully!'
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function getDeansInfo()
    {
        return $this->deanRepository->createQueryBuilder('d')
            ->select('d.firstName', 'd.lastName', 'd.title', 'd.email1', 'd.email2')
            ->andWhere('d.status = :status_val')
            ->setParameter('status_val', 1)
            ->getQuery()->getResult();
    }


    // [search-sort-filter by query]:
    public function getListVoteSession($listQuery)
    {
        $queryBuilder = $this->voteSessionRepository->createQueryBuilder('v')->select()
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

        return $queryBuilder->getQuery()->getResult();
    }

    public function buildVoteSessionListQuery($params)
    {
        $voteSessionListQuery = new VoteSessionListQuery();

        foreach ($params as $key => $value) {
            if ($key === "status") {
                $voteSessionListQuery->conditions[$key] = $value;
            }
        }

        return $voteSessionListQuery;
    }

    // ================ default:
    public function getVoteSessionById($id)
    {
        return $this->voteSessionRepository->find((int) $id);
    }

    public function getVoteSessionByYear($year)
    {
        $criteria = ['year' => $year];
        return $this->voteSessionRepository->findOneBy((array) $criteria);
    }
}
