<?php

namespace Alpixel\Bundle\MenuBundle\Builder;

use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    protected $entityManager;
    protected $knpFactory;

    function __construct(EntityManager $entityManager, FactoryInterface $knpFactory)
    {
        $this->entityManager = $entityManager;
        $this->knpFactory    = $knpFactory;
    }

    public function findMenu($machineName, $locale)
    {
        $repository = $this->entityManager->getRepository('AlpixelMenuBundle:Menu');
        $menu       = $repository->findOneMenuByMachineNameAndLocale($machineName, $locale);

        $items = $menu->getItems()->toArray();
        $tab   = [];
    }

    public function getTree($items, &$tab)
    {
        // if (is_array($items) === false) {
        //     return $items;
        // }

        // foreach ($items as $parentKey => $parent) {
        //     if ($parent->getParent() === null) {
        //         $tab[$parent->getId()][] = $parent;
        //         unset($items[$parentKey]);
        //     }
        // }

        // foreach ($tab as $key => $value) {
        //     foreach ($items as $childKey => $child) {
        //         if (isset($element) === true && $element !== null) {
        //             if($child->getParent() === $element) {
        //                 $tab[$key][] = $child;
        //             }
        //             $element = null;
        //         }
        //         else if($child->getParent() === $value) {
        //             $tab[$key][] = $child;
        //         }
        //     }
        // }

        // return;
    }
}
