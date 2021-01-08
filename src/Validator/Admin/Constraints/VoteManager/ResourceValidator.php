<?php

namespace App\Validator\Admin\Constraints\VoteManager;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Service\Admin\VoteManagerService;

class ResourceValidator extends ConstraintValidator
{
    private $voteManagerService;
    public function __construct(VoteManagerService $voteManagerService)
    {
        $this->voteManagerService = $voteManagerService;
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
        
        if (!$this->isIssetVoteSession($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', 'No resource was found on the server!')
            ->addViolation();
        }
    }

    public function isIssetVoteSession($value)
    {
        return $this->voteManagerService->getVoteSessionById($value) == null ? false : true;
    }
}
