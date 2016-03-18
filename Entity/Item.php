<?php

namespace Alpixel\Bundle\MenuBundle\Entity;

use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Alpixel\Bundle\MenuBundle\Model\MenuInterface;
use Alpixel\Bundle\MenuBundle\Validator\Constraints\RouteExists;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="alpixel_menu_item")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\MenuBundle\Entity\Repository\ItemRepository")
 */
class Item implements ItemInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="item_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Gedmo\SortableGroup
     *
     * @ORM\ManyToOne(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Item", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="item_id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Item", mappedBy="parent")
     */
    protected $children;

    /**
     * @Gedmo\SortableGroup
     *
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
     * @RouteExists
     * @ORM\Column(name="uri", type="text", nullable=false)
     */
    protected $uri;

    /**
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    protected $position;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Get string defined.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get menu.
     *
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set menu.
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
     * Get parent Item.
     *
     * @return null\Item
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent Item.
     *
     * @param ItemInterface $menu
     *
     * @return self
     */
    public function setParent(ItemInterface $item = null)
    {
        $this->parent = $item;

        return $this;
    }

    /**
     * Get children of Item.
     *
     * @return ArrayCollection (Item)
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set Item from ArrayCollection.
     *
     * @param null\ArrayCollection
     *
     * @return self
     */
    public function addChildren(ArrayCollection $collection)
    {
        foreach ($collection as $item) {
            $this->setChildren($item);
        }

        return $this;
    }

    /**
     * Set children of Item.
     *
     * @param Item $item
     *
     * @return self
     */
    public function setChildren(ItemInterface $item)
    {
        if ($this->children->contains($item) === false) {
            $this->children->add($item);
        }

        return $this;
    }

    /**
     * Remove children.
     *
     * @param ItemInterface $item
     *
     * @return $this
     */
    public function removeChildren(ItemInterface $item)
    {
        if ($this->children->contains($item) === true) {
            $this->children->removeElement($item);
        }

        return $this;
    }

    /**
     * Get name displayed in Item.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name displayed in Item.
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
     * Get URL.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set URL.
     *
     * @param string $url
     *
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get position of Item.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position.
     *
     * @param int $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
