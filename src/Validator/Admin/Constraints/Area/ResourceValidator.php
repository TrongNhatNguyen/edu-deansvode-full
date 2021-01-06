<?php

namespace App\Validator\Admin\Constraints\Area;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Service\Admin\ZoneService;

class ResourceValidator extends ConstraintValidator
{
    private $zoneService;
    public function __construct(ZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Resource) {
            throw new UnexpectedTypeException($constraint, Resource::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'params is required!')
            ->addViolation();
        }

        if ($this->isIssetZone($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Resource is required!')
            ->addViolation();
        }
    }

    public function isIssetZone($value)
    {
        return $this->zoneService->getZoneById($value) == null ? true : false;
    }
}
