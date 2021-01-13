<?php

namespace App\Validator\Admin\Constraints\Area;

use App\Service\Zone\ZoneFetcher;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Validator\Characters\ValidSpecialCharacters;

class IdentitiesValidator extends ConstraintValidator
{
    private $zoneFetcher;

    public function __construct(ZoneFetcher $zoneFetcher)
    {
        $this->zoneFetcher = $zoneFetcher;
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
            ->setParameter('{{ string }}', 'Alias is required!')
            ->setParameter('properties', 'slug')
            ->addViolation();
        }

        foreach ($value as $key => $string) {
            if ($key === 'id') {
                continue;
            }

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
            ->setParameter('{{ string }}', 'Alias already exists!')
            ->setParameter('properties', 'slug')
            ->addViolation();
        }
    }

    public function isIssetName($value)
    {
        if ($this->getCurrentZone($value)->getName() != $value['name']) {
            return $this->zoneFetcher->getZoneByName($value['name']) == null ? false : true;
        }

        return false;
    }

    public function isIssetSlug($value)
    {
        if ($this->getCurrentZone($value)->getSlug() != $value['slug']) {
            return $this->zoneFetcher->getZoneBySlug($value['slug']) == null ? false : true;
        }

        return false;
    }

    public function getCurrentZone($value)
    {
        return $this->zoneFetcher->getZoneById($value['id']);
    }
}
