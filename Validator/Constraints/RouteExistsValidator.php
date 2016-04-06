<?php

namespace Alpixel\Bundle\MenuBundle\Validator\Constraints;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RouteExistsValidator extends ConstraintValidator
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function validate($value, Constraint $constraint)
    {
        $match = false;
        if (strpos($value, '/') === 0) {
            $routeCollection = $this->router->getRouteCollection()->all();
            foreach ($routeCollection as $name => $route) {
                if ($match = $route->getPath() === $value) {
                    break;
                }
            }
        } else {
            $handle = curl_init($value);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            if ($httpCode >= 200 && $httpCode < 300) {
                $match = true;
            }
        }

        if ($match === false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
