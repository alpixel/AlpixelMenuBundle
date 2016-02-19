<?php

namespace Alpixel\Bundle\MenuBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface MenuInterface
{
    /**
     * Get string defined.
     *
     * @return string
     */
    public function __toString();

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
     * Get the name the value displayed to the administrator.
     *
     * @return self
     */
    public function getName();

    /**
     * Set the name the value displayed to the administrator.
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get the items.
     *
     * @return object
     */
    public function getItems();

    /**
     * Add Items objects from ArrayCollection.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $items
     *
     * @return self
     */
    public function addItems(ArrayCollection $items);

    /**
     * Remove Item object in ArrayCollection.
     *
     * @param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     *
     * @return self
     */
    public function removeItem(ItemInterface $item);

    /**
     * Set items for the menu.
     *
     * @param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     *
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
     */
    public function setItem(ItemInterface $item);

    /**
     * Get the locale language.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set the locale language.
     *
     * @param string
     *
     * @return self
     */
    public function setLocale($locale);
}
