<?php

namespace Alpixel\Bundle\MenuBundle\Twig;

use Alpixel\Bundle\MenuBundle\Builder\MenuBuilder;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;
use UnexpectedValueException;

class MenuExtension extends Twig_Extension
{
    protected $builder;

    function __construct(MenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('alpixel_get_menu', array($this, 'get')),
            new Twig_SimpleFunction('alpixel_render_menu', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        );
    }

    public function get($machineName, $locale = null)
    {
        return $this->builder->createKnpMenu($machineName, $locale);
    }

    public function render(Twig_Environment $twig, $machineName, $locale = null)
    {
        $menu = $this->get($machineName, $locale);

        return $twig->render();
    }

    public function getName()
    {
        return 'alpixel_menu_bundle_twig_menu_extension';
    }
}
