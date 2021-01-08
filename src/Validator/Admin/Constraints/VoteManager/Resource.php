<?php

namespace App\Validator\Admin\Constraints\VoteManager;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Resource extends Constraint
{
    public $message = '{{ string }}';
}
