<?php

namespace App\Service;

use App\Service\VoteManager\VoteSessionCreator;
use App\Service\VoteManager\VoteSessionQueryBuilder;
use App\Service\VoteManager\VoteSessionUpdator;
use App\Util\TransactionUtil;

class VoteManagerService
{
    private $voteSessionCreator;
    private $voteSessionUpdator;
    private $voteSessionQueryBuilder;
    private $transactionUtil;

    public function __construct(
        VoteSessionCreator $voteSessionCreator,
        VoteSessionUpdator $voteSessionUpdator,
        VoteSessionQueryBuilder $voteSessionQueryBuilder,
        TransactionUtil $transactionUtil
    ) {
        $this->voteSessionCreator = $voteSessionCreator;
        $this->voteSessionUpdator = $voteSessionUpdator;
        $this->voteSessionQueryBuilder = $voteSessionQueryBuilder;
        $this->transactionUtil = $transactionUtil;
    }


    public function createNewVoteSession($createRequest)
    {
        $this->transactionUtil->begin();
        try {
            $voteSession = $this->voteSessionCreator->fromRequest($createRequest);

            $this->transactionUtil->persist($voteSession);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'update successfully!'
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateVoteSession($updateRequest)
    {
        $this->transactionUtil->begin();
        try {
            $voteSession = $this->voteSessionUpdator->fromRequest($updateRequest);

            $this->transactionUtil->persist($voteSession);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'create successfully!'
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }
    
    // [search-sort-filter by params]:
    public function listVoteSession($request)
    {
        $listQuery = $this->voteSessionQueryBuilder->buildVoteSessionListQuery($request);

        $listVoteSessionQuery = $this->voteSessionQueryBuilder->getVoteSessionByListQuery($listQuery);

        return $listVoteSessionQuery->getQuery()->getResult();
    }
}
