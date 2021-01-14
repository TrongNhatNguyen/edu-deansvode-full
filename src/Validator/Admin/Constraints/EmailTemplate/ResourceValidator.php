<?php

namespace App\Validator\Admin\Constraints\EmailTemplate;

use App\Service\EmailTemplate\EmailTemplateFetcher;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class ResourceValidator extends ConstraintValidator
{
    private $emailTemplateFetcher;

    public function __construct(EmailTemplateFetcher $emailTemplateFetcher)
    {
        $this->emailTemplateFetcher = $emailTemplateFetcher;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Resource) {
            throw new UnexpectedTypeException($constraint, Resource::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Params is required!')
            ->addViolation();
        }

        if (!$this->isIssetEmailTemp($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'No resource was found on the server!')
            ->addViolation();
        }
    }

    public function isIssetEmailTemp($value)
    {
        return $this->emailTemplateFetcher->getEmailTemplateById($value) == null ? false : true;
    }
}
