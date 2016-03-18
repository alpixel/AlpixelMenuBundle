<?php

namespace Alpixel\Bundle\MenuBundle\Controller\Admin\CRUD;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class MenuAdmin extends Admin
{
    // protected $baseRouteName    = 'app_admin_cms_menu';
    // protected $baseRoutePattern = 'cms/menu';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->addOrderBy($query->getRootAlias().'.locale', 'ASC');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
        $collection->add('item', $this->getRouterIdParameter().'/item');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'label'    => 'Label',
                'required' => true,
            ))
            ->add('locale', 'choice', array( //@Todo Replace choices by $container->getParameter('lunetics_locale.allowed_locales');
                'label'   => 'Langue',
                'choices' => array(
                    'fr' => 'fr',
                    'en' => 'en',
                    'it' => 'it',
                ),
                'required' => true,
            ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('machineName', null, array(
                'label'    => 'Nom de la machine',
                'required' => true,
            ))
            ->add('name', null, array(
                'label'    => 'Label',
                'required' => true,
            ))
            ->add('locale', null, array(
                'label'    => 'Langue',
                'required' => true,
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'item' => array(
                        'template' => 'AlpixelMenuBundle:CRUD:list__action_item.html.twig'
                    )
                )
            ))
        ;
    }
}
