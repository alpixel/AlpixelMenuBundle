<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Builder;

use Alpixel\Bundle\MenuBundle\Builder\MenuBuilder;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MenuBuilderTest extends KernelTestCase
{
    const MENU_BUILDER_SERVICE = 'alpixel_menu.builder';
    private $container;

    public function setup()
    {
        self::bootKernel();
        $this->container = static::$kernel->getcontainer();
    }

    public function testIsValidLocale()
    {
        $builder = $this->container->get(self::MENU_BUILDER_SERVICE);
    }
    public function getMenuBuilderInstance()
    {
        return new MenuBuilder(new EntityManager(), new FactoryInterface());
    }
}
