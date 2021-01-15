<?php

namespace App\Service;

use App\Message\SmsMailStartCampaign;
use App\Service\Dean\DeanQueryBuilder;
use App\Service\VoteManager\VoteSessionCreator;
use App\Service\VoteManager\VoteSessionQueryBuilder;
use App\Service\VoteManager\VoteSessionUpdator;
use App\Util\Helper\MailHelper;
use App\Util\TransactionUtil;
use Symfony\Component\Messenger\MessageBusInterface;

class VoteManagerService
{
    private $voteSessionCreator;
    private $voteSessionUpdator;
    private $voteSessionQueryBuilder;
    private $deanQueryBuilder;
    private $transactionUtil;
    private $bus;

    public function __construct(
        VoteSessionCreator $voteSessionCreator,
        VoteSessionUpdator $voteSessionUpdator,
        VoteSessionQueryBuilder $voteSessionQueryBuilder,
        DeanQueryBuilder $deanQueryBuilder,
        TransactionUtil $transactionUtil,
        MessageBusInterface $bus
    ) {
        $this->voteSessionCreator = $voteSessionCreator;
        $this->voteSessionUpdator = $voteSessionUpdator;
        $this->voteSessionQueryBuilder = $voteSessionQueryBuilder;
        $this->deanQueryBuilder = $deanQueryBuilder;
        $this->transactionUtil = $transactionUtil;
        $this->bus = $bus;
    }


    public function createNewVoteSession($createRequest)
    {
        $this->transactionUtil->begin();
        try {
            $voteSession = $this->voteSessionCreator->fromRequest($createRequest);

            $this->transactionUtil->persist($voteSession);
            $this->transactionUtil->commit();

            $listDean = $this->deanQueryBuilder->getAllDeansActive();
            $this->sendMailToDeans($listDean);

            return [
                'status' => 'success',
                'message' => 'update successfully!',
                'mailNotifi' => 'Mailing process has been added to the queue!'
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

    public function sendMailToDeans($listDean)
    {
        $mailType = MailHelper::MAILER;
        $messageSendMailDeans = new SmsMailStartCampaign($listDean, $mailType);
        
        return $this->bus->dispatch($messageSendMailDeans);
    }
    
    // [search-sort-filter by params]:
    public function listVoteSession($request)
    {
        $listQuery = $this->voteSessionQueryBuilder->buildVoteSessionListQuery($request);

        $listVoteSessionQuery = $this->voteSessionQueryBuilder->getVoteSessionByListQuery($listQuery);

        return $listVoteSessionQuery->getQuery()->getResult();
    }
}
