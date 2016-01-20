<?php

namespace Alpixel\Bundle\MenuBundle\Model;

use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Doctrine\Common\Collections\ArrayCollection;

interface MenuInterface
{
    /**
     * Get the machineName the key for querying a menu.
     *
     * @return string
     */
    public function getMachineName();

    /**
     * Set the machineName the key for querying a menu.
     *
     * @param string
     *
     * @return self
     */
    public function setMachineName($machineName);

    /**
     * Get the name the value displayed to the administrator
     *
     * @return self
     */
    public function getName();

    /**
     * Set the name the value displayed to the administrator
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get the items
     *
     * @return object
     */
    public function getItems();

    /**
     * Add Items objects from ArrayCollection
     *
     * @param ArrayCollection $items
     *
     * @return self
     */
    public function addItems(ArrayCollection $items);

    /**
     * Remove Item object in ArrayCollection
     *
     * @param Item $item
     *
     * @return self
     */
    public function removeItem(ItemInterface $item);

    /**
     * Set items for the menu
     *
     * @param null\ItemInterface $item
     *
     * @return Item
     */
    public function setItem(ItemInterface $item);

    /**
     * Get the locale language
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set the locale language
     *
     * @return self
     */
    public function setLocale($locale);
}
