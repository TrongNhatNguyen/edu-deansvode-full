<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailAddress extends Constraint
{
    public $message = '"{{ string }}"';
}
