<?php

namespace Alpixel\Bundle\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Alpixel\Bundle\MenuBundle\Model\MenuInterface;
use Alpixel\Bundle\MenuBundle\Model\ItemInterface;

/**
* @ORM\Table(name="alpixel_item")
* @ORM\Entity
*/
class Item implements ItemInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Gedmo\SortableGroup
     *
     * @ORM\ManyToOne(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Item")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="item_id")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Menu", inversedBy="items")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="menu_id")
     */
    protected $menu;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", nullable=false)
     */
    protected $url;

    /**
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    protected $position;

    /**
     * Get menu
     *
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set menu
     *
     * @param Menu $menu
     *
     * @return self
     */
    public function setMenu(MenuInterface $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get parent Item
     *
     * @return null\Item
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent Item
     *
     * @param ItemInterface $menu
     *
     * @return self
     */
    public function setParent(ItemInterface $item)
    {
        $this->parent = $item;

        return $this;
    }

    /**
     * Get name displayed in Item
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name displayed in Item
     *
     * @param string
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get position of Item
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }


    /**
     * Set position
     *
     * @param  int    $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
