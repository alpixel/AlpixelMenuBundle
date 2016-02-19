<?php

namespace Alpixel\Bundle\MenuBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface ItemInterface
{
    /**
     * Get string defined.
     *
     * @return string
     */
    public function __toString();

    /**
     * Get menu.
     *
     * @return \Alpixel\Bundle\MenuBundle\Model\MenuInterface
     */
    public function getMenu();

    /**
     * Set MenuInterface.
     *
     * @param \Alpixel\Bundle\MenuBundle\Model\MenuInterface $menu
     *
     * @return self
     */
    public function setMenu(MenuInterface $menu);

    /**
     * Get parent Item.
     *
     * @return null|\Alpixel\Bundle\MenuBundle\Model\MenuInterface
     */
    public function getParent();

    /**
     * Set parent Item.
     *
     * @param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     *
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
     *
     * @internal param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $menu
     */
    public function setParent(ItemInterface $item = null);

    /**
     * Get chidlren of Item.
     *
     * @return null\ArrayCollection (Item)
     */
    public function getChildren();

    /**
     * Set Item from ArrayCollection.
     *
     * @param ArrayCollection $collection
     *
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
     *
     * @internal param $ null\ArrayCollection
     */
    public function addChildren(ArrayCollection $collection);

    /**
     * Set chidlren of Item.
     *
     * @param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     *
     * @return \Alpixel\Bundle\MenuBundle\Model\ItemInterface
     */
    public function setChildren(ItemInterface $item);

    /**
     * Remove children.
     *
     * @param \Alpixel\Bundle\MenuBundle\Model\ItemInterface $item
     *
     * @return mixed
     */
    public function removeChildren(ItemInterface $item);

    /**
     * Get name displayed in Item.
     *
     * @return string
     */
    public function getName();

    /**
     * Set name displayed in Item.
     *
     * @param string
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get Uri.
     *
     * @return null|string
     */
    public function getUri();

    /**
     * Set URL.
     *
     * @param string $uri
     *
     * @return self
     */
    public function setUri($uri);

    /**
     * Get position of Item.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Set position.
     *
     * @param int $position
     *
     * @return self
     */
    public function setPosition($position);
}
