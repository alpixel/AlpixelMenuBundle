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
     * @param Menu|\Alpixel\Bundle\MenuBundle\Model\MenuInterface $menu
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
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
     * @param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
     * @internal param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $menu
     *
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
     * @param ArrayCollection $collection
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
     * @internal param $ null\ArrayCollection
     *
     */
    public function addChildren(ArrayCollection $collection = null);

    /**
     * Set chidlren of Item
     *
     * @param Item|\Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
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
    public function getUri();

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return self
     */
    public function setUri($uri);

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
