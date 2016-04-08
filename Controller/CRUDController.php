<?php

namespace Alpixel\Bundle\MenuBundle\Controller;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Pix\SortableBehaviorBundle\Controller\SortableAdminController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CRUDController extends Controller
{
    public function itemAction()
    {
        $object     = $this->admin->getSubject();
        $router     = $this->container->get('router');
        $parameters = [];

        if ($object instanceof Menu) {
            $parameters['menu'] = $object->getId();
        } else if ($object instanceof Item) {
            $parameters['item'] = $object->getId();
        }

        $url = $router->generate('admin_alpixel_menu_item_list', $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);

        return new RedirectResponse($url);
    }

    public function parentItemAction()
    {
        $object     = $this->admin->getSubject();
        $router     = $this->container->get('router');
        $route      = '';
        
        if ($object instanceof Item) {
            $parameters['item'] = $object->getId();
            $route = 'admin_alpixel_menu_item_list';
        } else if ($object instanceof Menu) {
            $route = 'admin_alpixel_menu_list';
        }

        $url = $router->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);

        return new RedirectResponse($url);
    }
}
