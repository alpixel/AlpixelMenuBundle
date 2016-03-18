<?php

namespace Alpixel\Bundle\MenuBundle\Controller\Admin\CRUD;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ItemAdmin extends Admin
{
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
        $query->addOrderBy($query->getRootAlias().'.position', 'ASC')
            ->join($query->getRootAlias().'.menu', 'm')
            ->where('m.id = :id')
            ->setParameters([
                'id' => $id,
            ]);

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, [
                'label'    => 'Label',
                'required' => true,
            ])
            ->add('uri', 'text', [
                'label'    => 'URI',
                'required' => true,
            ])
            ->add('position', null, [
                'label'    => 'Position',
                'required' => true,
            ])
        ;
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
            ->add('_action', 'actions', array(
                'actions' => [
                    'edit' => []
                ]
            ))
        ;
    }
}
