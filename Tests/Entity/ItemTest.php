<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Entity;

use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Alpixel\Bundle\MenuBundle\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $item = $this->getItemInstance();
        $this->assertNull($item->__toString());

        $test = 'Menu principal';
        $item->setName($test);
        $this->assertEquals($test, $item->__toString());
    }

    public function testName()
    {
        $item = $this->getItemInstance();
        $this->assertNull($item->getName());

        $test = 'Menu principal';
        $item->setName($test);
        $this->assertEquals($test, $item->getName());
    }

    public function testUri()
    {
        $item = $this->getItemInstance();
        $this->assertNull($item->getUri());

        $test = 'http://www.unicorn.fr';
        $item->setUri($test);
        $this->assertEquals($test, $item->getUri());
    }

    public function testSetPosition()
    {
        $item = $this->getItemInstance();
        $this->assertNull($item->getPosition());

        $test = 1;
        $item->setPosition($test);
        $this->assertEquals($test, $item->getPosition());
    }

    public function testMenu()
    {
        $item = $this->getItemInstance();
        $this->assertNull($item->getMenu());

        $item->setMenu($this->getMenuInstance());
        $this->assertInstanceOf(Menu::class, $item->getMenu());
    }

    public function testParent()
    {
        $item = $this->getItemInstance();
        $this->assertNull($item->getParent());

        $item->setParent($this->getItemInstance());
        $this->assertInstanceOf(Item::class, $item->getParent());
    }

    public function testChildren()
    {
        $item = $this->getItemInstance();
        $this->assertInstanceOf(ArrayCollection::class, $item->getChildren());
        $this->assertCount(0, $item->getchildren());

        $item->setChildren($this->getItemInstance());
        $this->assertInstanceOf(ArrayCollection::class, $item->getChildren());
        $this->assertCount(1, $item->getChildren());
        $this->assertCount(1, $item->getChildren()->toArray());

        $this->assertInstanceOf(Item::class, $item->getChildren()->first());

        $item->removeChildren($item->getChildren()->first());
        $this->assertCount(0, $item->getChildren()->toArray());

        $collection = new ArrayCollection();
        $collection->add($this->getItemInstance());
        $collection->add($this->getItemInstance());
        $item->addChildren($collection);
        $this->assertCount(2, $item->getChildren()->toArray());
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
