<?php

namespace Alpixel\Bundle\MenuBundle\Validator\Constraints;

use Alpixel\Bundle\MenuBundle\Utils\URLChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RouteExistsValidator extends ConstraintValidator
{
    private $checker;

    public function __construct(URLChecker $checker)
    {
        $this->checker = $checker;
    }

    public function validate($value, Constraint $constraint)
    {
        // If the first occurrence of $value is the # character it's an anchor any check is done
        if (strpos($value, '#') === 0) {
            return;
        }

        $match = true;
        $code = $this->checker->check($value);
        if ($code === URLChecker::URL_NOT_FOUND) {
            $match = false;
        }

        if ($match === false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
