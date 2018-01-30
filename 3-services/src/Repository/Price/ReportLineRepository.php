<?php

namespace App\Repository\Price;

use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ReportLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportLine::class);
    }

    /**
     * @return ReportLine[]
     */
    public function getLinesForReport(Report $report): array
    {
        return $this
            ->createQueryBuilder('reportLine')
            ->join('reportLine.commodity', 'commodity')
            ->addSelect('commodity')
            ->andWhere('reportLine.report = :report')
            ->setParameter('report', $report)
            ->addOrderBy('reportLine.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ReportLine[]
     */
    public function getLinesForReportByType(Report $report, $type): array
    {
        return $this
            ->createQueryBuilder('reportLine')
            ->join('reportLine.commodity', 'commodity')
            ->addSelect('commodity')
            ->andWhere('reportLine.report = :report')
            ->andWhere('commodity.type = :type')
            ->setParameter('report', $report)
            ->setParameter('type', $type)
            ->addOrderBy('reportLine.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
