<?php

namespace Alpixel\Bundle\MenuBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{
    public function findOneMenuByMachineNameAndLocale($machineName, $locale)
    {
        return $this->createQueryBuilder('m')
            ->join('m.items', 'i')
            ->where('m.machineName = :machineName')
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
