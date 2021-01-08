<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class FullNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof FullName) {
            throw new UnexpectedTypeException($constraint, FullName::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Full-name is required!')
            ->addViolation();
        }

        if (!preg_match("/^[a-zA-Z\'\-\040]+$/", $value)) {
             $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Invalid full-name!')
            ->addViolation();
        }
    }
}
