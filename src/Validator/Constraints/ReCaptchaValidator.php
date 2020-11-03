<?php

namespace App\Validator\Constraints;

use App\Validator\Constraints\ReCaptcha;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReCaptchaValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReCaptcha) {
            throw new UnexpectedTypeException($constraint, ReCaptcha::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $reCaptchaString = $value;
        // reCAPTCHA response verification
        $verifyResponse = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret='
            .$_ENV['GOOGLE_RECAPTCHA_SECRET_KEY']
            .'&response='.$reCaptchaString
        );
        // Decode JSON data
        $responseReCaptcha = json_decode($verifyResponse);
        if (!$responseReCaptcha->success) {
            $this->context->buildViolation($constraint->message)
                // ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
