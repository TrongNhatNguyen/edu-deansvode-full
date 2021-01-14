<?php

namespace App\Validator\Admin\Constraints\EmailTemplate;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Resource extends Constraint
{
    public $message = '{{ string }}';
}
