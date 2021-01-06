<?php

namespace App\Validator\Admin\Constraints\Area;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Service\Admin\ZoneService;
use App\Validator\Characters\ValidSpecialCharacters;

class SlugValidator extends ConstraintValidator
{
    private $zoneService;
    public function __construct(ZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Slug) {
            throw new UnexpectedTypeException($constraint, Slug::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Alias is required!')
            ->addViolation();
        }

        if (!ValidSpecialCharacters::isValidSpecialCharacters($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Alias contains special characters!')
            ->addViolation();
        }

        if (!$this->isIssetSlug($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Alias already exists!')
            ->addViolation();
        }
    }

    public function isIssetSlug($value)
    {
        return $this->zoneService->getZoneBySlug($value) == null ? true : false;
    }
}
