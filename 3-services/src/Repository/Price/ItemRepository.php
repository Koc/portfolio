<?php

namespace App\Repository\Price;

use App\Entity\Price\Commodity;
use App\Entity\Price\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Literal;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param Commodity[] $commodities
     * @param \DateTime[] $dates
     *
     * @return Item[]
     */
    public function getItemsForCommoditiesByDates(array $commodities, array $dates): array
    {
        if (!$commodities || !$dates) {
            return [];
        }

        $queryBuilder = $this
            ->createQueryBuilder('item')
            ->andWhere('item.commodity IN (:commodities)')
            ->setParameter('commodities', $commodities);

        $dateParams = [];
        foreach ($dates as $i => $date) {
            $param = 'date_'.$i;
            $queryBuilder->setParameter($param, $date);
            $dateParams[] = new Literal(':'.$param);
        }

        $datesFilter = $queryBuilder->expr()
            ->in('DATE(item.publishedAt)', $dateParams);

        $queryBuilder->andWhere($datesFilter);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
