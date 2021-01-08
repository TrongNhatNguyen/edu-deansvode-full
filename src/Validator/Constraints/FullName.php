<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FullName extends Constraint
{
    public $message = '{{ string }}';
}
