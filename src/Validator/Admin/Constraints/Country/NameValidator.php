<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Validator\Characters\ValidSpecialCharacters;
use App\Service\Admin\CountryService;

class NameValidator extends ConstraintValidator
{
    private $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Name) {
            throw new UnexpectedTypeException($constraint, Name::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Name is required!')
            ->addViolation();
        }

        if (!ValidSpecialCharacters::isValidSpecialCharacters($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Name contains special characters!')
            ->addViolation();
        }

        if (!$this->isIssetName($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Name already exists!')
            ->addViolation();
        }
    }

    public function isIssetName($value)
    {
        return $this->countryService->getCountryByName($value) == null ? true : false;
    }
}
