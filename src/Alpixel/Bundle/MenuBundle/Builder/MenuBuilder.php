<?php

namespace Alpixel\Bundle\MenuBundle\Builder;

use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem as KnpMenuItem;

class MenuBuilder
{
    protected $entityManager;
    protected $factory;
    protected $knpMenu;

    function __construct(EntityManager $entityManager, FactoryInterface $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory       = $factory;
        $this->knpMenu       = null;
    }

    public function getKnpMenu()
    {
        return $this->knpMenu;
    }

    public function setKnpMenu(KnpMenuItem $knpMenu)
    {
        $this->knpMenu = $knpMenu;

        return $this;
    }

    /**
     * Create KnpMenuItem
     *
     * @param  string $machineName The name of menu
     * @param  string $locale      Language code (Recommanded ISO-639)
     *
     * @return KnpMenuItem         Get formatted menu
     */
    public function createKnpMenu($machineName, $locale)
    {
        $repository = $this->entityManager->getRepository('AlpixelMenuBundle:Menu');
        $menu       = $repository->findOneMenuByMachineNameAndLocale($machineName, $locale);
        $items      = $menu->getItems()->toArray();

        $this->setKnpMenu($this->factory->createItem('root'));
        foreach ($items as $item) {
            if($item->getParent() === null) {
                $this->getTree($this->knpMenu, $item);
            }
        }

        return $this->getKnpMenu();
    }

    public function getTree(KnpMenuItem $knpMenu, ItemInterface $item, KnpMenuItem $parent = null)
    {
        $menuItem = ($parent === null) ? $knpMenu->addChild($item) : $parent->addChild($item);

        foreach ($item->getChidlren() as $child) {
            $this->getTree($knpMenu, $child, $menuItem);
        }

        return $menuItem;
    }
}
