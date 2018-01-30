<?php

namespace App\Repository\WorldPrice;

use App\Entity\WorldPrice\Commodity;
use App\Entity\WorldPrice\Item;
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
     * @param array $commodities
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @return Item[]
     */
    public function getItemsForCommoditiesByInterval(array $commodities, $dateStart, $dateEnd): array
    {
//        dump($dateStart, $dateEnd);
//        exit();
        if (!$commodities || !$dateStart || !$dateEnd) {
            return [];
        }

        /** @var Item[] $items */
        $items = $this
            ->createQueryBuilder('item')
            ->andWhere('item.commodity IN (:commodities)')
            ->setParameter('commodities', $commodities)
            ->andWhere('DATE(item.publishedAt) BETWEEN :dateStart AND :dateEnd')
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateEnd', $dateEnd)
            ->orderBy('item.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();

        $itemsByWeeks = [];
        foreach ($items as $item) {
            $itemsByWeeks[$item->getPublishedAt()->format('W')][$item->getCommodity()->getId()][] = $item;
        }

        return $itemsByWeeks;
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
