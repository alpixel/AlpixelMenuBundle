<?php

namespace Alpixel\Bundle\MenuBundle\Model;

use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Alpixel\Bundle\MenuBundle\Model\MenuInterface;
use Doctrine\Common\Collections\ArrayCollection;

interface ItemInterface
{
    /**
     * Get string defined
     *
     * @return string
     */
    public function __toString();

    /**
     * Get menu
     *
     * @return Menu
     */
    public function getMenu();

    /**
     * Set menu
     *
     * @param Menu $menu
     *
     * @return self
     */
    public function setMenu(MenuInterface $menu);

    /**
     * Get parent Item
     *
     * @return null\Item
     */
    public function getParent();

    /**
     * Set parent Item
     *
     * @param ItemInterface $menu
     *
     * @return self
     */
    public function setParent(ItemInterface $item = null);

    /**
     * Get chidlren of Item
     *
     * @return null\ArrayCollection (Item)
     */
    public function getChidlren();

    /**
     * Set Item from ArrayCollection
     *
     * @param null\ArrayCollection
     *
     * @return self
     */
    public function addChildren(ArrayCollection $collection = null);

    /**
     * Set chidlren of Item
     *
     * @param Item      $item
     *
     * @return self
     */
    public function setChidlren(ItemInterface $item);

    /**
     * Get name displayed in Item
     *
     * @return string
     */
    public function getName();

    /**
     * Set name displayed in Item
     *
     * @param string
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url);

    /**
     * Get position of Item
     *
     * @return integer
     */
    public function getPosition();


    /**
     * Set position
     *
     * @param  int    $position
     *
     * @return self
     */
    public function setPosition($position);
}
