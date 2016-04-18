<?php

namespace Alpixel\Bundle\MenuBundle\Controller\Admin\CRUD;

use Alpixel\Bundle\MenuBundle\Utils\URLChecker;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
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
        $isNew   = ($this->id($subject) === null) ? true : false;

        if ($isNew === true) {
            $idMenu = $this->getRequest()->query->getInt('create-menu');
            $idItem = $this->getRequest()->query->getInt('create-item');
        } else {
            $idMenu  = $subject->getMenu()->getId();
            $idItem  = ($subject->getParent() !== null) ? $subject->getParent()->getId() : 0;
        }

        $formMapper
            ->add('menu', null, [
                'label'         => 'Menu',
                'required'      => true,
                'property'      => 'name',
                'query_builder' => function (EntityRepository $entityRepository) use ($idMenu) {
                    $query = $entityRepository->createQuerybuilder('m');
                    if ($idMenu === 0) {
                        return $query;
                    }

                    return $query
                        ->where('m.id = :id')
                        ->setParameter('id', $idMenu);
                },
            ]);

        if ($idItem === 0 && $idMenu === 0 || $isNew === false && $subject->getParent() !== null || $isNew === true && $idItem > 0) {
            $formMapper->add('parent', null, [
                'label'    => 'Item parent',
                'required' => true,
                'property' => 'name',
                'query_builder' => function (EntityRepository $entityRepository) use ($idItem) {
                    $query = $entityRepository->createQuerybuilder('i');
                    if ($idItem === 0) {
                        return $query;
                    }

                    return $query
                        ->where('i.id = :id')
                        ->setParameter('id', $idItem);
                }
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
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $this->last_position = $this->positionService->getLastPosition($this->getRoot()->getClass());

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

    public function postUpdate($object = null)
    {
        if ($object !== null && $object->getUri() !== null) {
            $container = $this->getConfigurationPool()->getContainer();
            $checker = $container->get('alpixel_menu.utils.url_checker');
            $url = $object->getUri();
            if ($checker->check($url) === URLChecker::URL_PROBLEM) {
                $session = $container->get('session');
                $session->getFlashBag()->add('warning', 'Cependant une erreur semble être apparue quand nous avons tenté d\'analyser la page "'.$url.'". Vous devriez vérifier que le lien spécifié n\'affiche aucune erreur.');
            }
        }
    }
}
