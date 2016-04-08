<?php

namespace Alpixel\Bundle\MenuBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class URLChecker
 * @package Alpixel\Bundle\MenuBundle\Utils
 */
class URLChecker
{
    const URL_VALID     = 0;
    const URL_NOT_FOUND = 1;
    const URL_PROBLEM   = 2;

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function check($url)
    {
        if (strpos($url, '/') === 0) {
            $context = $this->router->getContext();
            $baseUrl = $context->getScheme().'://'.$context->getHost().$context->getBaseUrl();
            $url = $baseUrl.$url;
        }

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        // $httpCode (success) >= 200 && $httpCode (redirect) < 310 for all http request accepted
        if ($httpCode >= 200 && $httpCode < 310) {
            return self::URL_VALID;
        } else if ($httpCode === 404) {
            return self::URL_NOT_FOUND;
        }

        return self::URL_PROBLEM;
    }
}