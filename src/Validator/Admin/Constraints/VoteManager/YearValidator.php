<?php

namespace App\Validator\Admin\Constraints\VoteManager;

use App\Service\Admin\VoteManagerService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;


class YearValidator extends ConstraintValidator
{
    private $voteManagerService;
    public function __construct(VoteManagerService $voteManagerService)
    {
        $this->voteManagerService = $voteManagerService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Year) {
            throw new UnexpectedTypeException($constraint, Year::class);
        }

        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Year is required!')
            ->addViolation();
        }

        if (!preg_match('/^[1-9][0-9]*$/', $value) || !($value > 1000 && $value < 2100)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Invalid year!')
            ->addViolation();
        }

        if (!$this->isIssetYear($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'Year already exists!')
            ->addViolation();
        }
    }

    public function isIssetYear($value)
    {
        return $this->voteManagerService->getVoteSessionByYear($value) == null ? true : false;
    }
}
