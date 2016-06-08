<?php

namespace Alpixel\Bundle\MenuBundle\Controller;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Pix\SortableBehaviorBundle\Controller\SortableAdminController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Alexis BUSSIERES <alexis@alpixel.fr>
 */
class ItemCRUDController extends Controller
{
    private function deleteItems($items)
    {
        $menu = $this->get('alpixel_menu.menu.menu');
        $menu->deleteItems($items);
    }

    public function batchActionDelete(ProxyQueryInterface $query)
    {
        $this->admin->checkAccess('batchDelete');

        try {
            $this->deleteItems($query->execute());
            $this->addFlash('sonata_flash_success', 'flash_batch_delete_success');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function deleteAction($id)
    {
        $request = $this->getRequest();
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('delete', $object);

        $preResponse = $this->preDelete($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($this->getRestMethod() == 'DELETE') {
            // check the csrf token
            $this->validateCsrfToken('sonata.delete');

            $objectName = $this->admin->toString($object);

            try {
                $this->deleteItems([$object]);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'ok'), 200, array());
                }

                $this->addFlash(
                    'sonata_flash_success',
                    $this->admin->trans(
                        'flash_delete_success',
                        array('%name%' => $this->escapeHtml($objectName)),
                        'SonataAdminBundle'
                    )
                );
            } catch (ModelManagerException $e) {
                $this->handleModelManagerException($e);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'error'), 200, array());
                }

                $this->addFlash(
                    'sonata_flash_error',
                    $this->admin->trans(
                        'flash_delete_error',
                        array('%name%' => $this->escapeHtml($objectName)),
                        'SonataAdminBundle'
                    )
                );
            }

            return $this->redirectTo($object);
        }

        return $this->render($this->admin->getTemplate('delete'), array(
            'object' => $object,
            'action' => 'delete',
            'csrf_token' => $this->getCsrfToken('sonata.delete'),
        ), null);
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
            } else {
                $router = $this->get('router');
                $url = $router->generate('admin_alpixel_menu_menu_list');
            }
        }

        if (!$url) {
            foreach (['edit', 'show'] as $route) {
                if ($this->admin->hasRoute($route) && $this->admin->isGranted(strtoupper($route), $object)) {
                    $url = $this->admin->generateObjectUrl($route, $object);
                    break;
                }
            }
        }

        if (!$url) {
            $router = $this->get('router');
            $url = $router->generate('admin_alpixel_menu_menu_list');
        }

        return new RedirectResponse($url);
    }

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
}
