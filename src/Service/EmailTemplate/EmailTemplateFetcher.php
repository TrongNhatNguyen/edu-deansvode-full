<?php

namespace App\Service\EmailTemplate;

use App\Repository\EmailTemplateRepository;

class EmailTemplateFetcher
{
    private $emailTemplateRepository;

    public function __construct(EmailTemplateRepository $emailTemplateRepository)
    {
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    
    public function getEmailTemplateById(int $id)
    {
        return $this->emailTemplateRepository->find($id);
    }

    public function getAllEmailTemplates()
    {
        return $this->emailTemplateRepository->findBy([], ['id' => 'DESC']);
    }

    // active:
    public function getEmailTemplateActive()
    {
        return $this->emailTemplateRepository->findOneBy(['status' => 1]);
    }
}
