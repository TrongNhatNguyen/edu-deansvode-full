<?php

namespace App\Validator\Admin\Constraints\Area;

use App\Service\Zone\ZoneFetcher;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Validator\Characters\ValidSpecialCharacters;

class NameValidator extends ConstraintValidator
{
    private $zoneFetcher;

    public function __construct(ZoneFetcher $zoneFetcher)
    {
        $this->zoneFetcher = $zoneFetcher;
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
        return $this->zoneFetcher->getZoneByName($value) == null ? true : false;
    }
}
