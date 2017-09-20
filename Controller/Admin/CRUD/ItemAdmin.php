<?php

namespace Alpixel\Bundle\MenuBundle\Controller\Admin\CRUD;

use Alpixel\Bundle\MenuBundle\Entity\Item;
use Alpixel\Bundle\MenuBundle\Utils\URLChecker;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class ItemAdmin extends Admin
{
    public $last_position = 0;

    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'position',
    ];

    public function setPositionService(\Pix\SortableBehaviorBundle\Services\PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
        $collection->add('item', $this->getRouterIdParameter().'/item');
    }

    public function getPersistentParameters()
    {
        $parameters = [];
        if ($this->getRequest() instanceof Request) {
            $query = $this->getRequest()->query;
            if ($query->has('menu')) {
                $parameters['menu'] = $query->getInt('menu');
            }

            if ($query->has('item')) {
                $parameters['item'] = $query->getInt('item');
            }
        }

        return $parameters;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        if ($this->getRequest() instanceof Request) {
            $requestQuery = $this->getRequest()->query;
            if ($requestQuery->has('item')) {
                $parentId = $requestQuery->getInt('item');
                $query->join($query->getRootAlias().'.parent', 'p')
                    ->andWhere('p.id = :parentId')
                    ->setParameters([
                        'parentId' => $parentId,
                    ]);
            } elseif ($requestQuery->has('menu')) {
                $menuId = $requestQuery->getInt('menu');
                $query
                    ->join($query->getRootAlias().'.menu', 'm')
                    ->leftJoin($query->getRootAlias().'.parent', 'p')
                    ->where('m.id = :menuId')
                    ->andwhere('p.id IS NULL')
                    ->setParameters([
                        'menuId' => $menuId,
                    ]);
            }
        }

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $isNew = ($this->id($subject) === null) ? true : false;
        $request = $this->getRequest();

        $idItem = null;
        $idMenu = null;
        if($request->query->has('uniqid')) {
            $formId = $request->query->get('uniqid');
            if ($request->request->has($formId)) {
                $formData = $request->request->get($formId);
                $idMenu   = $formData['create-menu'];
                $idItem   = $formData['create-item'];
            }
        }

        if ($idMenu === null && $idItem === null) {
            if ($isNew === true) {
                $idMenu = $this->getRequest()->query->get('create-menu');
                $idItem = $this->getRequest()->query->get('create-item');
            } else {
                $idMenu = $subject->getMenu()->getId();
                $idItem = ($subject->getParent() !== null) ? $subject->getParent()->getId() : null;
            }
        }

        $formMapper
            ->add('menu', null, [
                'label'         => 'Menu',
                'required'      => true,
                'property'      => 'name',
                'query_builder' => function (EntityRepository $entityRepository) use ($idMenu) {
                    $query = $entityRepository->createQuerybuilder('m');
                    if ($idMenu === null) {
                        return $query;
                    }

                    return $query
                        ->where('m.id = :id')
                        ->setParameter('id', $idMenu);
                },
            ]);

        if ($idItem != null) {
            $pool       = $this->getConfigurationPool();
            $doctrine   = $pool->getContainer()->get('doctrine.orm.default_entity_manager');
            $parentItem = $doctrine->getRepository('AlpixelMenuBundle:Item')->find($idItem);

            $formMapper->add('parent', null, [
                'label'         => 'Item parent',
                'required'      => true,
                'property'      => 'name',
                'data'          => $parentItem,
                'query_builder' => function (EntityRepository $repository) use ($parentItem) {
                    $qb = $repository->createQueryBuilder('i');

                    if ($parentItem->getParent() === null) {
                         return $qb->where('i.parent IS NULL')
                             ->andWhere('i.menu = :id')
                             ->setParameter('id', $parentItem->getMenu());
                    }

                    return $qb->where('i.id IN (:ids)')
                        ->setParameter('ids', $parentItem->getParent()->getChildren());
                },
            ]);
        }

        $formMapper
            ->add('name', null, [
                'label'    => 'Nom du menu à afficher',
                'required' => true,
            ])
            ->add('uri', 'text', [
                'label'    => 'URI',
                'required' => true,
            ])
            ->add('create-menu', 'hidden', [
                'required' => false,
                'data'     => $idMenu,
                'mapped'   => false,
            ])
            ->add('create-item', 'hidden', [
                'required' => false,
                'data'     => $idItem,
                'mapped'   => false,
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, [
                'label'    => 'Label',
                'required' => true,
            ])
            ->add('uri', null, [
                'label'    => 'URI',
                'required' => true,
            ])
            ->add('position', null, [
                'label'    => 'Position',
                'required' => true,
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'move' => [
                        'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig',
                    ],
                    'item' => [
                        'template' => 'AlpixelMenuBundle:CRUD:list__action_item.html.twig',
                    ],
                ],
            ]);
    }

    public function checkUri($object = null)
    {
        if($object === null) {
            return;
        }

        $form    = $this->getForm();
        $request = $this->getRequest();
        $uriType = $request->request->get('uri_type');

        if (!Item::uriTypeExists($uriType)) {
            $form->get('uri')->addError(new FormError(
               'Le type d\'url est invalide !'
            ));

            return;
        }

        if ($uriType === Item::URI_TYPE_ANCHOR) {
            return;
        }

        if ($uriType === Item::URI_TYPE_EXTERNAL) {
            $uri = $form->get('uri')->getData();
            if (preg_match('/^https?:\/\//', $uri) === 0) {
                $form->get('uri')->addError(new FormError(
                    'Votre lien doit débuter par "http://" ou "https://"'
                ));
            }
        }
    }

    public function preValidate($object = null)
    {
        $this->checkUri($object);
    }

    public function postUpdate($object = null)
    {
        if ($object !== null) {
            $url = $object->getUri();
            if ($url !== null) {
                $container = $this->getConfigurationPool()->getContainer();
                $checker = $container->get('alpixel_menu.utils.url_checker');
                if ($checker->check($url) === URLChecker::URL_PROBLEM) {
                    $session = $container->get('session');
                    $session->getFlashBag()->add('warning', 'Cependant une erreur semble être apparue quand nous avons tenté d\'analyser la page "'.$url.'". Vous devriez vérifier que le lien spécifié n\'affiche aucune erreur.');
                }
            }
        }
    }
}
