<?php

namespace Alpixel\Bundle\MenuBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RouteExists extends Constraint
{

    public $message = 'L\'url "%string%" n\'existe pas.';

    public function validatedBy()
    {
        return 'route_exists_validator';
    }
}
