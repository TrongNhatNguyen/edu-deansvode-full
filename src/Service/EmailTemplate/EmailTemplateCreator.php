<?php

namespace App\Service\EmailTemplate;

use App\Entity\EmailTemplate;

class EmailTemplateCreator
{
    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray($data)
    {
        $emailTemplate = new EmailTemplate();

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
