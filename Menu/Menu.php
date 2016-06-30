<?php

namespace Alpixel\Bundle\MenuBundle\Menu;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Doctrine\ORM\EntityManager;

/**
 * @author Alexis BUSSIERES <alexis@alpixel.fr>
 */
class Menu
{
    const DELETE_STRATEGY_MOVE_CHILDREN = 'delete.strategy.move_children';

    protected $entityManager;

    /**
     * Menu constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $item An array of object Item
     * @param string $strategy Strategy to use
     */
    public function deleteItem($item, $strategy)
    {
        $this->deleteItems([$item], $strategy);
    }

    /**
     * This method delete an array of Item by different strategy.
     *
     * Strategies available:
     * self::DELETE_STRATEGY_MOVE_CHILDREN Remove Item and set children to the same level of the deleted Item
     *
     * @param $items
     * @param string $strategy Strategy to use
     */
    public function deleteItems($items, $strategy = self::DELETE_STRATEGY_MOVE_CHILDREN)
    {
        if (!is_array($items)) {
            throw new \InvalidArgumentException('The "$items" parameters is not an array.');
        }

        if (empty($items)) {
            return;
        }

        switch ($strategy) {
            case self::DELETE_STRATEGY_MOVE_CHILDREN:
                $this->deleteItemsMoveChildren($items);
                break;
            default:
                throw new \InvalidArgumentException('The "$stategy" parameter must be a non empty string.');
        }

        $this->entityManager->flush();
    }

    /**
     * This method delete Item object and manage his children by self::DELETE_STRATEGY_MOVE_CHILDREN strategy
     *
     * @param array $items An array of object Item
     */
    private function deleteItemsMoveChildren($items)
    {

        foreach ($items as $item) {
            if (!is_object($item) || !$item instanceof Item) {
                throw new \InvalidArgumentException(sprintf(
                    'An error occurred during the operation, 
                    the value must be an instance object of %s'
                ), Item::class);
            }

            $this->entityManager->remove($item);

            $children = $item->getChildren();
            $itemParent = $item->getParent();

            if (empty($children) || empty($itemParent)) {
                continue;
            }

            $itemParentChildren = $itemParent->getChildren();
            foreach ($children as $child) {
                $child->setParent($itemParent);
                $itemParentChildren->add($child);
            }

            $this->entityManager->persist($itemParent);
        }
    }

    /**
     * @return array An array of available delete strategies
     */
    public static function getDeleteStrategiesAvailable()
    {
        return [
          self::DELETE_STRATEGY_MOVE_CHILDREN,
        ];
    }
}
