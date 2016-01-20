<?php

namespace Alpixel\Bundle\MenuBundle\Builder;

class MenuBuilder
{
    protected $entityManager;
    protected $knpFactory;

    function __construct(EntityManager $entityManager, FactoryInterface $knpFactory)
    {
        $this->entityManager = $entityManager;
        $this->knpFactory    = $knpFactory;
    }

    public function findMenu($machineName, $locale)
    {
        $repository = $this->entityManager->getRepository('AlpixelMenuBundle:Menu');
    }
}
