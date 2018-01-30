<?php

namespace App\Repository\WorldPrice;

use App\Entity\WorldPrice\Commodity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CommodityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commodity::class);
    }

    /**
     * @param array $ids
     * @return Commodity[]
     */
    public function getCommoditiesByIds(array $ids)
    {
        if (!$ids) {
            return [];
        }

        return $this->createQueryBuilder('c', 'c.id')
            ->select('c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}
