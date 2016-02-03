<?php

namespace Alpixel\Bundle\MenuBundle\Twig;

use Alpixel\Bundle\MenuBundle\Builder\MenuBuilder;

class MenuExtension extends \Twig_Extension
{
    protected $builder;

    function __construct(MenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function getFunction()
    {
        return array(
            new \Twig_SimpleFunction('alpixel_get_menu', array($this, 'get')),
            new \Twig_SimpleFunction('alpixel_get_menu', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function get($machineName, $locale)
    {
        return $this->builder->createKnpMenu($machineName, $locale);
    }

    public function render($machineName, $locale)
    {
        return $this->builder->render($machineName, $locale);
    }
}
