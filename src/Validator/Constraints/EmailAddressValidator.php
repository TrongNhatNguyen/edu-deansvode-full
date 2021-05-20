<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class EmailAddressValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EmailAddress) {
            throw new UnexpectedTypeException($constraint, EmailAddress::class);
        }


        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Email is required, please enter your email.')
            ->addViolation();
        } else {
            $email = $value;
            $email = htmlspecialchars(stripslashes(strip_tags($email)));
            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
            if (!preg_match($pattern, $email) ||
                !stristr($email, "@") ||
                !stristr($email, ".")
            ) {
                $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', 'Your email is invalid, please try again.')
                ->addViolation();
            }
        }
    }
}
