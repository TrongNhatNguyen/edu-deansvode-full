<?php

namespace App\Validator\Admin\Constraints\Country;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Identities extends Constraint
{
    public $message = '{{ string }}';
    public $properties = 'properties';
}
