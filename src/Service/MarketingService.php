<?php

namespace App\Service;

use App\Entity\EmailTemplate;
use App\Service\EmailTemplate\EmailTemplateCreator;
use App\Service\EmailTemplate\EmailTemplateFetcher;
use App\Service\EmailTemplate\EmailTemplateQueryBuilder;
use App\Service\EmailTemplate\EmailTemplateUpdator;
use App\Util\TransactionUtil;

class MarketingService
{
    private $emailTemplateCreator;
    private $emailTemplateUpdator;
    private $emailTemplateFetcher;
    private $emailTemplateQueryBuilder;
    private $transactionUtil;

    public function __construct(
        EmailTemplateCreator $emailTemplateCreator,
        EmailTemplateUpdator $emailTemplateUpdator,
        EmailTemplateFetcher $emailTemplateFetcher,
        EmailTemplateQueryBuilder $emailTemplateQueryBuilder,
        TransactionUtil $transactionUtil
    ) {
        $this->emailTemplateCreator = $emailTemplateCreator;
        $this->emailTemplateUpdator = $emailTemplateUpdator;
        $this->emailTemplateFetcher = $emailTemplateFetcher;
        $this->emailTemplateQueryBuilder = $emailTemplateQueryBuilder;
        $this->transactionUtil = $transactionUtil;
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

    // [filter by params]:
    public function listEmailTemplate($request)
    {
        $listQuery = $this->emailTemplateQueryBuilder->buildEmailTemplateListQuery($request);

        $listEmailTemplateQuery = $this->emailTemplateQueryBuilder->getEmailTemplateByListQuery($listQuery);

        return $listEmailTemplateQuery->getQuery()->getResult();
    }
}
