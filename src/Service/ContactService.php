<?php

namespace App\Service;

use App\Service\UserContact\UserContactCreator;
use App\Service\UserContact\UserContactUpdator;
use App\Util\TransactionUtil;
use Psr\Log\LoggerInterface;

class ContactService
{
    private $userContactCreator;
    private $userContactUpdator;
    private $transactionUtil;
    private $logger;

    public function __construct(
        UserContactCreator $userContactCreator,
        UserContactUpdator $userContactUpdator,
        TransactionUtil $transactionUtil,
        LoggerInterface $logger
    ) {
        $this->userContactCreator = $userContactCreator;
        $this->userContactUpdator = $userContactUpdator;
        $this->transactionUtil = $transactionUtil;
        $this->logger = $logger;
    }

    public function createUserContact($sendEmailRequest)
    {
        $this->transactionUtil->begin();
        try {
            $userContact = $this->userContactCreator->fromRequest($sendEmailRequest);
            
            $this->transactionUtil->persist($userContact);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'id' => $userContact->getId(),
                'message' => 'your idear successfully sent!',
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'message' => 'failed create user contact',
                'error' => [get_class($ex) => $ex->getMessage()]
            ];
        }
    }

    public function updateUserContact($updateRequest)
    {
        $this->transactionUtil->begin();
        try {
            $userContact = $this->userContactUpdator->fromArray($updateRequest);

            $this->transactionUtil->persist($userContact);
            $this->transactionUtil->commit();

            $this->logger->info('Successfully updated UserContact!');
            return exit;
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();
            $this->logger->info([get_class($ex) => $ex->getMessage()]);
            return ['error' => [get_class($ex) => $ex->getMessage()]];
        }
    }
}
