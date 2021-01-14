<?php

namespace App\Service\EmailTemplate;

class EmailTemplateUpdator
{
    private $emailTemplateFetcher;
    
    public function __construct(EmailTemplateFetcher $emailTemplateFetcher)
    {
        $this->emailTemplateFetcher = $emailTemplateFetcher;
    }

    public function fromRequest($request)
    {
        $data  = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray($data)
    {
        $emailTemplate = $this->emailTemplateFetcher->getEmailTemplateById($data['id']);

        foreach ($data as $field => $value) {
            if ($field === 'id') {
                continue;
            }

            $setter = 'set' . $field;

            if (!method_exists($emailTemplate, $setter)) {
                continue;
            }

            $emailTemplate->$setter($value);
        }

        return $emailTemplate;
    }
}
