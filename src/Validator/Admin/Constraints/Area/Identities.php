<?php

namespace App\Validator\Admin\Constraints\Area;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Identities extends Constraint
{
    public $message = '{{ string }}';
    public $properties = 'properties';
}
