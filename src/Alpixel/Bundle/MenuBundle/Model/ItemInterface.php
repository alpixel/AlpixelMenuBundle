<?php

namespace Alpixel\Bundle\MenuBundle\Model;

use Alpixel\Bundle\MenuBundle\Model\MenuInterface;

interface ItemInterface
{
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
    public function setParent(ItemInterface $menu = null);

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
