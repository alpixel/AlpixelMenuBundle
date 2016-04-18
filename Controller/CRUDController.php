<?php

namespace Alpixel\Bundle\MenuBundle\Controller;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Pix\SortableBehaviorBundle\Controller\SortableAdminController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CRUDController extends Controller
{
    public function itemAction()
    {
        $object = $this->admin->getSubject();
        $router = $this->container->get('router');
        $parameters = [];

        if ($object instanceof Menu) {
            $parameters['menu'] = $object->getId();
        } elseif ($object instanceof Item) {
            $parameters['menu'] = $object->getMenu()->getId();
            $parameters['item'] = $object->getId();
        }

        $url = $router->generate('admin_alpixel_menu_item_list', $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);

        return new RedirectResponse($url);
    }

    protected function redirectTo($object)
    {
        $request = $this->getRequest();

        $url = false;
        $params = [];

        if ($object instanceof Item) {
            if ($object->getParent() !== null) {
                $itemId = $object->getParent()->getId();
                $params['list']['item'] = $itemId;
                $params['create']['create-item'] = $itemId;
            }
            $menuId = $object->getMenu()->getId();
            $params['list']['menu'] = $menuId;
            $params['create']['create-menu'] = $menuId;
        } elseif ($object instanceof Menu) {
            $menuId = $object->getId();
            $params['list']['menu'] = $menuId;
            $params['create']['create-menu'] = $menuId;
        }

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->admin->generateUrl('list', $params['list']);
        } elseif (null !== $request->get('btn_create_and_list')) {
            $url = $this->admin->generateUrl('list', $params['list']);
        }

        if (null !== $request->get('btn_create_and_create')) {
            if ($this->admin->hasActiveSubClass()) {
                $params['create']['subclass'] = $request->get('subclass');
            }
            $url = $this->admin->generateUrl('create', $params['create']);
        }

        if ($this->getRestMethod() === 'DELETE') {
            if ($object instanceof Item) {
                $url = $this->admin->generateUrl('list', $params['list']);
            }
            else {
                $router = $this->get('router');
                $url= $router->generate('admin_alpixel_menu_menu_list');
            }
        }

        if (!$url) {
            foreach (array('edit', 'show') as $route) {
                if ($this->admin->hasRoute($route) && $this->admin->isGranted(strtoupper($route), $object)) {
                    $url = $this->admin->generateObjectUrl($route, $object);
                    break;
                }
            }
        }

        if (!$url) {
            $router = $this->get('router');
            $url= $router->generate('admin_alpixel_menu_menu_list');
        }

        return new RedirectResponse($url);
    }
}
