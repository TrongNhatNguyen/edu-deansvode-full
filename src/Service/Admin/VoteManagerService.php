<?php

namespace App\Service\Admin;

use App\DTO\QueryObject\VoteSession\VoteSessionListQuery;
use App\Entity\VoteSession;
use App\Message\SmsMailCampaign;
use App\Repository\DeanRepository;
use App\Repository\VoteSessionRepository;
use App\Util\Helper\MailHelper;

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

    public function updateVoteSession($id)
    {
        try {
            $voteSession = $this->voteSessionRepository->find($id);

            $voteSession->setClosedAt(new \DateTime('now'));
            $voteSession->setStatus(0);
            $voteSession->setUpdatedAt(new \DateTime('now'));

            return $this->voteSessionRepository->voteSessionAction($voteSession);
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function createNewVoteSession($data)
    {
        try {
            $data['status'] = 1;

            $newVoteSession = new VoteSession;

            $newVoteSession->setYear($data['year']);
            $newVoteSession->setBeginAt(new \DateTime('now'));
            $newVoteSession->setStatus($data['status']);
            $newVoteSession->setCreatedAt(new \DateTime('now'));
            $newVoteSession->setUpdatedAt(new \DateTime('now'));

            return $this->voteSessionRepository->voteSessionAction($newVoteSession);
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
            ->setParameter('status_val', '1')
            ->getQuery()->getResult();
    }


    // save value params (search-sort-filter by query):
    public function getListVoteSession($reqParams)
    {
        $listQuery = $this->buildVoteSessionListQuery($reqParams);
        $queryBuilder = $this->getListQueryByConditions($listQuery);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getListQueryByConditions($ListQuery)
    {
        $queryBuilder = $this->voteSessionRepository->createQueryBuilder('v')->select()
        ->addOrderBy('v.id', 'DESC');

        if (!empty($ListQuery->conditions)) {
            foreach ($ListQuery->conditions as $key => $value) {
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
}
