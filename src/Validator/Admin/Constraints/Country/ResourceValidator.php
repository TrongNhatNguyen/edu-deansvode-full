<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Service\Admin\CountryService;

class ResourceValidator extends ConstraintValidator
{
    private $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
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
        
        if (!$this->isIssetCountry($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Resource is required!')
            ->addViolation();
        }
    }

    public function isIssetCountry($value)
    {
        return $this->countryService->getCountryById($value) == null ? false : true;
    }
}
