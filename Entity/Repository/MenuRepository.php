<?php

namespace Alpixel\Bundle\MenuBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{
    public function findOneMenuByMachineNameAndLocale($machineName, $locale)
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.items', 'i')
            ->where('m.machineName = :machineName')
            ->andWhere('m.locale = :locale')
            ->setParameters([
                'machineName' => $machineName,
                'locale'      => $locale,
            ])
            ->getQuery()
            ->getSingleResult();
    }
}
