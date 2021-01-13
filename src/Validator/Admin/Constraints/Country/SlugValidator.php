<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Service\Country\CountryFetcher;
use App\Validator\Characters\ValidSpecialCharacters;

class SlugValidator extends ConstraintValidator
{
    private $countryFetcher;

    public function __construct(CountryFetcher $countryFetcher)
    {
        $this->countryFetcher = $countryFetcher;
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
        return $this->countryFetcher->getCountryBySlug($value) == null ? true : false;
    }
}
