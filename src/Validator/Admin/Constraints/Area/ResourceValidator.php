<?php

namespace App\Validator\Admin\Constraints\Area;

use App\Service\Zone\ZoneFetcher;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class ResourceValidator extends ConstraintValidator
{
    private $zoneFetcher;

    public function __construct(ZoneFetcher $zoneFetcher)
    {
        $this->zoneFetcher = $zoneFetcher;
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

        if ($this->isIssetZone($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'No resource was found on the server!')
            ->addViolation();
        }
    }

    public function isIssetZone($value)
    {
        return $this->zoneFetcher->getZoneById($value) == null ? true : false;
    }
}
