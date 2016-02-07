<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Builder;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Alpixel\Bundle\MenuBundle\Builder\MenuBuilder;
use Alpixel\Bundle\MenuBundle\Entity\Repository\MenuRepository;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MenuBuilderTest extends KernelTestCase
{
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }

    public function getMenuBuilder()
    {
        return $this->container->get('alpixel_menu.builder');
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

    public function testCreateKnpMenu()
    {
        $entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repositoryMock = $this->getMockBuilder(MenuRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array('findOneMenuByMachineNameAndLocale'))
            ->getMock()
        ;
        $repositoryMock->method('findOneMenuByMachineNameAndLocale')->willReturn($this->getMenuContext('fr'));
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $builder = new MenuBuilder($entityManagerMock, $this->container->get('knp_menu.factory'));
        $knpMenu = $builder->createKnpMenu('main', 'fr');
        $this->assertInstanceOf(MenuItem::class, $knpMenu);

        $knpMenu = $builder->createKnpMenu('main');
        $this->assertInstanceOf(MenuItem::class, $knpMenu);
    }

    public function getMenuContext($locale)
    {
        $menuData = $this->getMenuData()[$locale];
        $menu = new Menu();
        $menu->setName($menuData['name']);
        $menu->setMachineName($menuData['machineName']);
        $menu->setLocale($menuData['locale']);
        $collectionA = new ArrayCollection();
        for ($i = 1; $i <= 5; $i++) {
            $itemA = $this->newItem($menu, $i);
            $collectionB = new ArrayCollection();
            for ($j = 1; $j <= 5; $j++) {
                $itemB = $this->newItem($menu, $j, $itemA);
                $collectionB->add($itemB);
                $collectionC = new ArrayCollection();
                for ($k = 1; $k <= 5; $k++) {
                    $itemC = $this->newItem($menu, $k);
                    $collectionC->add($itemC);
                }
                $itemB->addChildren($collectionC);
            }
            $itemA->addChildren($collectionB);
            $collectionA->add($itemA);
        }
        $menu->addItems($collectionA);

        return $menu;
    }

    public function newItem(Menu $menu, $i, Item $parentItem = null)
    {
        $item = new Item();
        $item->setMenu($menu);
        $item->setName('Item '.$i);
        $item->setPosition($i);
        $item->setUri('http://alpixel.fr');

        if ($parentItem !== null) {
            $item->setParent($parentItem);
        }

        return $item;
    }

    public function getMenuData()
    {
        return [
            'fr' => [
                'name' => 'Menu principal',
                'machineName' => 'main',
                'locale' => 'fr',
            ],
            'en' => [
                'name' => 'Main menu',
                'machineName' => 'main',
                'locale' => 'en',
            ],
        ];
    }

}