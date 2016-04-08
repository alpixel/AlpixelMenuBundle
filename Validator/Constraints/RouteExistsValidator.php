<?php

namespace Alpixel\Bundle\MenuBundle\Validator\Constraints;

use Alpixel\Bundle\MenuBundle\Utils\URLChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RouteExistsValidator extends ConstraintValidator
{
    private $checker;
    private $session;

    public function __construct(URLChecker $checker)
    {
        $this->checker = $checker;
    }

    public function validate($value, Constraint $constraint)
    {
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
