<?php

namespace Alpixel\Bundle\MenuBundle\DataFixtures\ORM;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMenuData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $menu = new Menu();
        $menu->setMachineName('main');
        $menu->setName('Menu principal');
        $menu->setLocale('fr');

        $item = new Item();
        $item->setUri('/');
        $item->setName('Homepage');

        $menu->addItem($item);

        $manager->persist($item);
        $manager->persist($menu);
        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}
