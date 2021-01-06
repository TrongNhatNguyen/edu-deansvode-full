<?php

namespace App\Validator\Admin\Constraints\Area;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Slug extends Constraint
{
    public $message = '{{ string }}';
}
