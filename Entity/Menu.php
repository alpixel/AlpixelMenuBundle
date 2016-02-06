<?php

namespace Alpixel\Bundle\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Alpixel\Bundle\MenuBundle\Model\MenuInterface;
use Alpixel\Bundle\MenuBundle\Model\ItemInterface;

/**
* @ORM\Table(name="alpixel_menu")
* @ORM\Entity(repositoryClass="Alpixel\Bundle\MenuBundle\Entity\Repository\MenuRepository")
*/
class Menu implements MenuInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Alpixel\Bundle\MenuBundle\Entity\Item", mappedBy="menu")
     */
    protected $items;

    /**
     * @var string
     *
     * @ORM\Column(name="machine_name", type="string", length=255, nullable=false)
     */
    protected $machineName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=10, nullable=false)
     */
    protected $locale;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Get string defined
     *
     * @return string
     */
    public function __toString()
    {
        return $this->machineName;
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
     * Get the machineName the key for querying a menu.
     *
     * @return string
     */
    public function getMachineName()
    {
        return $this->machineName;
    }

    /**
     * Set the machineName the key for querying a menu.
     *
     * @param string
     *
     * @return self
     */
    public function setMachineName($machineName)
    {
        $this->machineName = $machineName;

        return $this;
    }

    /**
     * Get the name the value displayed to the administrator
     *
     * @return self
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name the value displayed to the administrator
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the items
     *
     * @return ArrayCollection of Item object
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Remove Item obejct from ArrayCollection items
     *
     * @param  ItemInterface $item
     *
     * @return self
     */
    public function removeItem(ItemInterface $item)
    {
        if($this->items->contains($item) === true) {
            $this->items->removeElement($item);
        }

        return $this;
    }

    /**
     * Add Items in items ArrayCollection
     *
     * @param ArrayCollection of Item object $items
     *
     * @return self
     */
    public function addItems(ArrayCollection $items)
    {
        foreach ($items as $item) {
            if($this->items->contains($item) === false) {
                $this->setItem($item);
            }
        }

        return $this;
    }

    /**
     * Set items for the menu
     *
     * @param null\ItemInterface $item
     *
     * @return Item
     */
    public function setItem(ItemInterface $item)
    {
        $this->items->add($item);

        return $this;
    }

    /**
     * Get the locale language
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the locale language
     *
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
