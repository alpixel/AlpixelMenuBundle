<?php

namespace Alpixel\Bundle\MenuBundle\Builder;

use Alpixel\Bundle\MenuBundle\Exception\LocaleException;
use Alpixel\Bundle\MenuBundle\Model\ItemInterface;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem as KnpMenuItem;
use Symfony\Component\HttpFoundation\RequestStack;
use UnexpectedValueException;

class MenuBuilder
{
    protected $currentUri;
    protected $entityManager;
    protected $factory;
    protected $knpMenu;
    protected $defaultLocale;
    protected $request;

    /**
     * MenuBuilder constructor.
     *
     * @param EntityManager    $entityManager
     * @param FactoryInterface $factory
     */
    public function __construct(RequestStack $requestStack, EntityManager $entityManager, FactoryInterface $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;

        $request = $requestStack->getCurrentRequest();
        $this->request = $request;
        $this->currentUri = $request->getRequestUri();
    }

    /**
     * Check if locale is valid.
     *
     * @param $locale
     *
     * @return bool
     */
    public static function isValidLocale($locale)
    {
        if (is_string($locale) && !empty($locale)) {
            return true;
        }

        return false;
    }

    /**
     * The parameter $locale must be defined in your
     * symfony configuration file under parameters.
     *
     * @param $locale String
     *
     * @return $this
     */
    public function setDefaultLocale($locale)
    {
        if (self::isValidLocale($locale)) {
            $this->defaultLocale = $locale;

            return $this;
        }

        throw new LocaleException('
            The $locale parameter must be a non empty string or the locale is not defined
            under the Symfony parameters configuration.
        ');
    }

    /**
     * Return default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Check if the machineName is valid.
     *
     * @param $machineName
     *
     * @return bool
     */
    public static function isValidMachineName($machineName)
    {
        if (is_string($machineName) && !empty($machineName)) {
            return true;
        }

        return false;
    }

    /**
     * Retrun null or a KnpMenuItem instance.
     *
     * @return null|KnpMenuItem
     */
    public function getKnpMenu()
    {
        return $this->knpMenu;
    }

    /**
     * Set the KnpMenuItem instance.
     *
     * @param KnpMenuItem $knpMenu
     *
     * @return $this
     */
    public function setKnpMenu(KnpMenuItem $knpMenu)
    {
        $this->knpMenu = $knpMenu;

        return $this;
    }

    /**
     * Create KnpMenuItem.
     *
     * @param string $machineName The name of menu
     * @param string $locale      Language code (Recommanded ISO-639)
     *
     * @return KnpMenuItem Get formatted menu
     */
    public function createKnpMenu($machineName, $locale = null)
    {
        if (!self::isValidMachineName($machineName)) {
            throw new UnexpectedValueException('The parameter $machineName must be a non empty string');
        }

        if ($locale === null) {
            $locale = $this->getDefaultLocale();
        } elseif (!self::isValidLocale($locale)) {
            throw new LocaleException();
        }

        $this->setKnpMenu($this->factory->createItem('root'));

        $menu = $this->entityManager
                     ->getRepository('AlpixelMenuBundle:Menu')
                     ->findOneMenuByMachineNameAndLocale($machineName, $locale);

        $items = $menu->getItems()->toArray();

        foreach ($items as $item) {
            if ($item->getParent() === null) {
                $this->getTree($this->knpMenu, $item);
            }
        }

        return $this->getKnpMenu();
    }

    /**
     * Create tree un KnpMenuItem.
     *
     * @param KnpMenuItem      $knpMenu
     * @param ItemInterface    $item
     * @param KnpMenuItem|null $parent
     *
     * @return KnpMenuItem A formatted KnpMenu
     */
    protected function getTree(KnpMenuItem $knpMenu, ItemInterface $item, KnpMenuItem $parent = null)
    {
        $menuItem = ($parent === null) ? $knpMenu->addChild($item) : $parent->addChild($item);

        if (($uri = $item->getUri()) !== null) {
            if ($uri[0] == '/') {
                $baseUri = $this->request->getBasePath().
                           $this->request->getBaseURL().
                           $uri;
                $uri = $this->request->getSchemeAndHttpHost().$baseUri;

                if ($baseUri === $this->currentUri) {
                    $menuItem->setCurrent(true);
                }
            }
            $menuItem->setUri($uri);
        }

        $menuItem->setAttributes([
            'position' => $item->getPosition(),
            'slug'     => $item->getSlug(),
        ]);

        foreach ($item->getChildren() as $child) {
            if ($this->currentUri == $child->getUri()) {
                $this->setParentActive($child->getParent());
            }
            $this->getTree($knpMenu, $child, $menuItem);
        }

        return $menuItem;
    }
}
