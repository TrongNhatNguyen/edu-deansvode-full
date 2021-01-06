<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Service\Admin\CountryService;
use App\Validator\Characters\ValidSpecialCharacters;

class IdentitiesValidator extends ConstraintValidator
{
    private $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Identities) {
            throw new UnexpectedTypeException($constraint, Identities::class);
        }

        if (empty($value['name'])) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Name is required!')
            ->setParameter('properties', 'name')
            ->addViolation();
        }

        if (empty($value['slug'])) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Slug is required!')
            ->setParameter('properties', 'slug')
            ->addViolation();
        }

        if (empty($value['isoCode'])) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Iso-code is required!')
            ->setParameter('properties', 'isoCode')
            ->addViolation();
        }

        foreach ($value as $key => $string) {
            if (!ValidSpecialCharacters::isValidSpecialCharacters($string)) {
                $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $key.' contains special characters!')
                ->setParameter('properties', $key)
                ->addViolation();
            }
        }

        if ($this->isIssetName($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Name already exists!')
            ->setParameter('properties', 'name')
            ->addViolation();
        }

        if ($this->isIssetSlug($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Slug already exists!')
            ->setParameter('properties', 'slug')
            ->addViolation();
        }

        if ($this->isIssetIsoCode($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Iso-code already exists!')
            ->setParameter('properties', 'isoCode')
            ->addViolation();
        }
    }

    public function isIssetName($value)
    {
        if ($this->getCurrentCountry($value)->getName() != $value['name']) {
            return $this->countryService->getCountryByName($value['name']) == null ? false : true;
        }

        return false;
    }

    public function isIssetSlug($value)
    {
        if ($this->getCurrentCountry($value)->getSlug() != $value['slug']) {
            return $this->countryService->getCountryBySlug($value['slug']) == null ? false : true;
        }

        return false;
    }

    public function isIssetIsoCode($value)
    {
        if ($this->getCurrentCountry($value)->getIsoCode() != $value['isoCode']) {
            return $this->countryService->getCountryByIsoCode($value['isoCode']) == null ? false : true;
        }

        return false;
    }

    public function getCurrentCountry($value)
    {
        return $this->countryService->getCountryById($value['id']);
    }
}
