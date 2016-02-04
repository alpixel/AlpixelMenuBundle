<?php

namespace Alpixel\Bundle\MenuBundle\Exception;

class LocaleException extends \InvalidArgumentException
{
    public function __construct($message = null)
    {
        if ($message === null) {
            $message = 'The parameter must be a non empty string';
        }

        parent::__construct($message);
    }
}