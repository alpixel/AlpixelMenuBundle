<?php

namespace Alpixel\Bundle\MenuBundle\Entity;

use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Alpixel\Bundle\MenuBundle\Model\MenuInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\ManyToOne(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Item", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="item_id")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Item", mappedBy="parent")
     */
    protected $children;

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
        $this->chidlren = new ArrayCollection();
    }

    /**
     * Get string defined
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get Id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

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
    public function setParent(ItemInterface $item = null)
    {
        $this->parent = $item;

        return $this;
    }


    /**
     * Get chidlren of Item
     *
     * @return null\ArrayCollection (Item)
     */
    public function getChidlren()
    {
        return $this->children;
    }

    /**
     * Set Item from ArrayCollection
     *
     * @param null\ArrayCollection
     *
     * @return self
     */
    public function addChildren(ArrayCollection $collection = null)
    {
        foreach ($collection as $item) {
            if($this->chidlren->contains($item) === false) {
                $this->setChidlren($item);
            }
        }

        return $this;
    }

    /**
     * Set chidlren of Item
     *
     * @param Item      $item
     *
     * @return self
     */
    public function setChidlren(ItemInterface $item)
    {
        $this->chidlren->add($item);

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
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set URL
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
