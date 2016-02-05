<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Controller;

use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Alpixel\Bundle\MenuBundle\Entity\Item;

class MenuTest extends \PHPUnit_Framework_TestCase
{
    public function testMachineName()
    {
        $menu = $this->getMenuMock();
        $this->assertNull($menu->getMachineName());

        $test = 'main';
        $menu->setMachineName($test);
        $this->assertEquals($test, $menu->getMachineName());
    }
    
    public function testName()
    {
        $menu = $this->getMenuMock();
        $this->assertNull($menu->getName());

        $test = 'Menu principal';
        $menu->setName($test);
        $this->assertEquals($test, $menu->getName());
    }

    public function tesLocale()
    {
        $menu = $this->getMenuMock();
        $this->assertNull($menu->getLocale());

        $test = 'fr';
        $menu->setLocale($test);
        $this->assertEquals($test, $menu->getLocale());
    }

    public function getItemMock()
    {
        return $this->getMockForAbstractClass(Item::class);
    }

    public function getMenuMock()
    {
        return $this->getMockForAbstractClass(Menu::class);
    }
}
