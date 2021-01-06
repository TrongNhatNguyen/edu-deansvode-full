<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Service\Admin\CountryService;
use App\Validator\Characters\ValidSpecialCharacters;

class IsoCodeValidator extends ConstraintValidator
{
    private $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsoCode) {
            throw new UnexpectedTypeException($constraint, IsoCode::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Iso-code is required!')
            ->addViolation();
        }

        if (!ValidSpecialCharacters::isValidSpecialCharacters($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Iso-code contains special characters!')
            ->addViolation();
        }

        if (!$this->isIssetIsoCode($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Iso-code already exists!')
            ->addViolation();
        }
    }

    public function isIssetIsoCode($value)
    {
        return $this->countryService->getCountryByIsoCode($value) == null ? true : false;
    }
}
