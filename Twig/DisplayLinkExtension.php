<?php

namespace Alpixel\Bundle\MenuBundle\Twig;

class DisplayLinkExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('alpixel_menu_link', [$this, 'menuLink'], ['needs_environment' => true]),
        ];
    }

    /**
     * Add app_dev.php in dev environment if the link is locale.
     *
     * @param \Twig_Environment $twig
     * @param $link
     *
     * @return string
     */
    public function menuLink(\Twig_Environment $twig, $link)
    {
        $globals = $twig->getGlobals();
        $app = $globals['app'];
        $env = $app->getEnvironment();

        if ($env === 'dev' && strpos($link, 'http') === false) {
            $dev = ($link[0] === '/') ? 'app_dev.php' : 'app_dev.php/';
            $link = $dev.$link;
        }

        return $link;
    }

    public function getName()
    {
        return 'alpixel_menu_menu_link';
    }
}
