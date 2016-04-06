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
            $context = $this->router->getContext();
            $baseUrl = $context->getScheme().'://'.$context->getHost().$context->getBaseUrl();
            $value = $baseUrl.$value;
        }

        $handle = curl_init($value);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        // $httpCode >= 200 && $httpCode < 300 for all http request accepted
        // $httpCode === 401 for page with authentification required
        if ($httpCode >= 200 && $httpCode < 300 || $httpCode === 401) {
            $match = true;
        }

        if ($match === false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
