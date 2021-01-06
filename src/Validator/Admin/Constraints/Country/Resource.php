<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Resource extends Constraint
{
    public $message = '{{ string }}';
}
