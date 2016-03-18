<?php

namespace Alpixel\Bundle\MenuBundle\Controller;

use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CRUDController extends Controller
{
    public function itemAction()
    {
        $menu = $this->admin->getSubject();

        if (!$menu instanceof Menu) {
            throw new NotFoundHttpException('Unable to find the object');
        }

        $router = $this->container->get('router');
        $url = $router->generate('admin_alpixel_menu_item_list', ['menu' => $menu->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);

        return new RedirectResponse($url);
    }
}
