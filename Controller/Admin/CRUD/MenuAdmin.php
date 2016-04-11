<?php

namespace Alpixel\Bundle\MenuBundle\Controller\Admin\CRUD;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class MenuAdmin extends Admin
{
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->addOrderBy($query->getRootAlias().'.locale', 'ASC');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->add('item', $this->getRouterIdParameter().'/item');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $locales = $container->getParameter('lunetics_locale.allowed_locales');
        $locales = array_combine($locales, $locales);

        $formMapper
            ->add('name', null, [
                'label'    => 'Label',
                'required' => true,
            ]);

        $security = $this->getSecurityHandler();
        $isAuthorized = $security->isGranted($this, 'ROLE_SUPER_ADMIN');

        if ($isAuthorized) {
            $formMapper
                ->add('locale', 'choice', [
                    'label'    => 'Langue',
                    'choices'  => $locales,
                    'required' => true,
                ])
                ->add('machineName', null, [
                    'label'    => 'Nom de la machine',
                    'required' => true,
                ]);
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('machineName', null, [
                'label'    => 'Nom de la machine',
                'required' => true,
            ])
            ->add('name', null, [
                'label'    => 'Label',
                'required' => true,
            ])
            ->add('locale', null, [
                'label'    => 'Langue',
                'required' => true,
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'item' => [
                        'template' => 'AlpixelMenuBundle:CRUD:list__action_item.html.twig',
                    ],
                    'add' => [
                        'template' => 'AlpixelMenuBundle:CRUD:add__action_item.html.twig',
                    ],
                ],
            ]);
    }
}
