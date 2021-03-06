<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Service\Country\CountryFetcher;

class ResourceValidator extends ConstraintValidator
{
    private $countryFetcher;

    public function __construct(CountryFetcher $countryFetcher)
    {
        $this->countryFetcher = $countryFetcher;
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
        
        if (!$this->isIssetCountry($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'No resource was found on the server!')
            ->addViolation();
        }
    }

    public function isIssetCountry($value)
    {
        return $this->countryFetcher->getCountryById($value) == null ? false : true;
    }
}
