<?php

namespace App\Service;

use App\Message\SmsMailTest;
use App\Service\EmailTemplate\EmailTemplateCreator;
use App\Service\EmailTemplate\EmailTemplateFetcher;
use App\Service\EmailTemplate\EmailTemplateQueryBuilder;
use App\Service\EmailTemplate\EmailTemplateUpdator;
use App\Util\Helper\MailHelper;
use App\Util\TransactionUtil;
use Symfony\Component\Messenger\MessageBusInterface;

class MarketingService
{
    private $emailTemplateCreator;
    private $emailTemplateUpdator;
    private $emailTemplateFetcher;
    private $emailTemplateQueryBuilder;
    private $transactionUtil;
    private $bus;

    public function __construct(
        EmailTemplateCreator $emailTemplateCreator,
        EmailTemplateUpdator $emailTemplateUpdator,
        EmailTemplateFetcher $emailTemplateFetcher,
        EmailTemplateQueryBuilder $emailTemplateQueryBuilder,
        TransactionUtil $transactionUtil,
        MessageBusInterface $bus
    ) {
        $this->emailTemplateCreator = $emailTemplateCreator;
        $this->emailTemplateUpdator = $emailTemplateUpdator;
        $this->emailTemplateFetcher = $emailTemplateFetcher;
        $this->emailTemplateQueryBuilder = $emailTemplateQueryBuilder;
        $this->transactionUtil = $transactionUtil;
        $this->bus = $bus;
    }


    public function createEmailTemplate($createRequest)
    {
        $this->transactionUtil->begin();
        try {
            $this->deactiveCurrentEmailTemplate();

            $emailTemplate = $this->emailTemplateCreator->fromRequest($createRequest);
            $this->transactionUtil->persist($emailTemplate);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'successfully created!'
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return[
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateEmailTemplate($updateRequest)
    {
        $this->transactionUtil->begin();
        try {
            $emailTemplate = $this->emailTemplateUpdator->fromRequest($updateRequest);
            $this->transactionUtil->persist($emailTemplate);
            $this->transactionUtil->commit();
            
            return [
                'status' => 'success',
                'message' => 'successfully update!'
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function loadEmailTemplate($loadTempRequest)
    {
        $this->transactionUtil->begin();
        try {
            $this->deactiveCurrentEmailTemplate();
            
            $emailTemplate = $this->emailTemplateUpdator->fromRequest($loadTempRequest);
            $this->transactionUtil->persist($emailTemplate);
            $this->transactionUtil->commit();

            return;
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function deactiveCurrentEmailTemplate()
    {
        $activeEmailTemp = $this->emailTemplateFetcher->getEmailTemplateActive();

        if (!$activeEmailTemp) {
            return ['status' => 'success'];
        }

        $data = [
            'id' => $activeEmailTemp->getId(),
            'status' => 0,
            'updatedAt' => new \DateTime('now')
        ];

        return $this->updateEmailTemplate($data);
    }

    public function sendMailTest($sendTestRequest)
    {
        try {
            $mailType = MailHelper::MAILER;
            $messageSendMailTest = new SmsMailTest($sendTestRequest, $mailType);
            $this->bus->dispatch($messageSendMailTest);

            return [
                'status' => 'success',
                'message' => 'successfully send mail!'
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    // [filter by params]:
    public function listEmailTemplate($request)
    {
        $listQuery = $this->emailTemplateQueryBuilder->buildEmailTemplateListQuery($request);

        $listEmailTemplateQuery = $this->emailTemplateQueryBuilder->getEmailTemplateByListQuery($listQuery);

        return $listEmailTemplateQuery->getQuery()->getResult();
    }
}
