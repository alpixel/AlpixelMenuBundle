<?php

namespace Alpixel\Bundle\MenuBundle\Entity\Repository;

use Alpixel\Bundle\MenuBundle\Entity\Menu;
use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    public function findItemsByMenu(Menu $menu)
    {
        return $this->createQueryBuilder('i')
            ->join('i.menu', 'm')
            ->where('m.id = :id')
            ->setParameters([
                'id' => $menu->getId(),
            ])
            ->getQuery()
            ->getResult();
    }
}
