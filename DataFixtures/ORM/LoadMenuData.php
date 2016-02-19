<?php

namespace Alpixel\Bundle\MenuBundle\DataFixtures\ORM;

use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Alpixel\Bundle\MenuBundle\Entity\Item;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMenuData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $objectManager)
    {
        $menu = new Menu();
        $menu->setMachineName('main');
        $menu->setName('Menu principal');

	$item = new Item;
        $item->setUri('/');
        $item->setName('Homepage');
        $menu->setItem($item);

        $manager->persist($item);
        $manager->persist($menu);
        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}