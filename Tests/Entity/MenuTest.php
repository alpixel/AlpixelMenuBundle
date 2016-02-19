<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Entity;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;

class MenuTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $menu = $this->getMenuInstance();
        $this->assertNull($menu->__toString());

        $test = 'Menu principal';
        $menu->setMachineName($test);
        $this->assertEquals($test, $menu->__toString());
    }

    public function testMachineName()
    {
        $menu = $this->getMenuInstance();
        $this->assertNull($menu->getMachineName());

        $test = 'main';
        $menu->setMachineName($test);
        $this->assertEquals($test, $menu->getMachineName());
    }

    public function testName()
    {
        $menu = $this->getMenuInstance();
        $this->assertNull($menu->getName());

        $test = 'Menu principal';
        $menu->setName($test);
        $this->assertEquals($test, $menu->getName());
    }

    public function testLocale()
    {
        $menu = $this->getMenuInstance();
        $this->assertNull($menu->getLocale());

        $test = 'fr';
        $menu->setLocale($test);
        $this->assertEquals($test, $menu->getLocale());
    }

    public function testItems()
    {
        $menu = $this->getMenuInstance();
        $this->assertInstanceOf(ArrayCollection::class, $menu->getItems());
        $this->assertCount(0, $menu->getItems());

        $menu->setItem($this->getItemInstance());
        $this->assertInstanceOf(ArrayCollection::class, $menu->getItems());
        $this->assertCount(1, $menu->getItems());

        $this->assertCount(1, $menu->getItems()->toArray());
        $this->assertInstanceOf(Item::class, $menu->getItems()->first());

        $menu->removeItem($menu->getItems()->first());
        $this->assertCount(0, $menu->getItems()->toArray());

        $collection = new ArrayCollection();
        $collection->add($this->getItemInstance());
        $collection->add($this->getItemInstance());
        $menu->addItems($collection);
        $this->assertCount(2, $menu->getItems()->toArray());
    }

    public function getItemInstance()
    {
        return new Item();
    }

    public function getMenuInstance()
    {
        return new Menu();
    }
}
