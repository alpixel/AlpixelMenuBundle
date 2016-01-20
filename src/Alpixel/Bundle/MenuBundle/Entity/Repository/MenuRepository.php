<?php

namespace Alpixel\Bundle\MenuBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{
    public function findOneMenu($machineName, $locale)
    {
        return $this->createQueryBuilder('m')
            ->join('m.items', 'i')
            ->where('m.machine = :machineName')
            ->andWhere('m.locale = :locale')
            ->setParameters(array(
                'machineName' => $machineName,
                'locale'      => $locale,
            ))
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
