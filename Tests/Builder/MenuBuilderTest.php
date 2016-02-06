<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Builder;

use Alpixel\Bundle\MenuBundle\Builder\MenuBuilder;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MenuBuilderTest extends KernelTestCase
{
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }

    public function testIsValidLocale()
    {
        $this->assertEquals(true, MenuBuilder::isValidLocale('fr'));
    }

    public function testIsValidMachineName()
    {
        $this->assertEquals(true, MenuBuilder::isValidMachineName('Machine Name'));
    }

    public function testSetDefaultLocale()
    {
        $entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $factoryMock = $this->getMockBuilder(FactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $builder = new MenuBuilder($entityManagerMock, $factoryMock);
        $this->assertNull($builder->getDefaultLocale());

        $test = 'fr';
        $builder->setDefaultLocale($test);
        $this->assertEquals($test, $builder->getDefaultLocale());
    }

    public function getMenuBuilder()
    {
        return $this->container->get('alpixel_menu.builder');
    }
}