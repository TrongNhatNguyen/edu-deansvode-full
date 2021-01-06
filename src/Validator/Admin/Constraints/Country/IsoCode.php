<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsoCode extends Constraint
{
    public $message = '{{ string }}';
}
