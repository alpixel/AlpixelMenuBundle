<?php

namespace Alpixel\Bundle\MenuBundle\Controller\Admin\CRUD;

use Alpixel\Bundle\MenuBundle\Utils\URLChecker;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

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
    }

    public function getPersistentParameters()
    {
        if (!$this->getRequest()) {
            return [];
        }

        return [
            'menu' => $this->getRequest()->query->getInt('menu'),
        ];
    }

    public function createQuery($context = 'list')
    {
        $id = $this->getRequest()->query->getInt('menu');
        $query = parent::createQuery($context);
        $query->join($query->getRootAlias().'.menu', 'm')
            ->where('m.id = :id')
            ->setParameters([
                'id' => $id,
            ]);

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $id = $this->getRequest()->query->getInt('menu');
        $isNew = ($this->id($this->getSubject()) === null) ? false : true;

        if ($isNew === false) {
            $formMapper
                ->add('menu', null, [
                    'label'         => 'Menu',
                    'required'      => true,
                    'property'      => 'name',
                    'query_builder' => function (EntityRepository $entityRepository) use ($id) {
                        $query = $entityRepository->createQuerybuilder('m');
                        if ($id === null) {
                            return $query;
                        }

                        return $query
                            ->where('m.id = :id')
                            ->setParameter('id', $id);
                    },
                ]);
        }

        $formMapper
            ->add('menu', null, [
                'label'         => 'Menu',
                'required'      => true,
                'property'      => 'name',
                'query_builder' => function (EntityRepository $entityRepository) use ($id) {
                    $query = $entityRepository->createQuerybuilder('m');
                    if ($id === null) {
                        return $query;
                    }

                    return $query
                        ->where('m.id = :id')
                        ->setParameter('id', $id);
                },
            ])
            ->add('parent', null, [
                'label'    => 'Item parent',
                'required' => false,
                'property' => 'name',
            ])
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
                ],
            ]);
    }

    public function postUpdate($object = null)
    {
        if ($object !== null && $object->getUri() !== null) {
            $container  = $this->getConfigurationPool()->getContainer();
            $checker    = $container->get('alpixel_menu.utils.url_checker');
            $url        = $object->getUri();
            if ($checker->check($url) === URLChecker::URL_PROBLEM) {
                $session = $container->get('session');
                $session->getFlashBag()->add('warning', 'Cependant une erreur semble être apparue quand nous avons tenté d\'analyser la page "'.$url.'". Vous devriez vérifier que le lien spécifié n\'affiche aucune erreur.');
            }
        }
    }
}
