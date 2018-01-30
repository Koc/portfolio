<?php

namespace App\Reporting\Report\Plants;

use App\Entity\Price\Commodity;
use App\Entity\Price\Item;
use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use App\Reporting\Source\ReportSource;

class PlantsReportSource extends ReportSource
{
    private $baseDate;

    /**
     * @var Item[]|array<Commodity.id, array<Item.getDateKey, Item>>
     */
    private $items;

    /**
     * @var \DateTime[]
     */
    private $dates;

    /**
     * @param ReportLine[] $lines
     */
    public function __construct(
        Report $report,
        array $lines,
        \DateTime $baseDate,
        array $items,
        array $dates
    ) {
        parent::__construct($report, $lines);

        $this->baseDate = $baseDate;
        $this->items = $items;
        $this->dates = $dates;
    }

    /**
     * @return \DateTime
     */
    public function getBaseDate(): \DateTime
    {
        return $this->baseDate;
    }

    public function getItemByCommodity(Commodity $commodity, $date): ?Item
    {
        return $this->getItemByCommodityAndDate($commodity, $date);
    }

    private function getItemByCommodityAndDate(Commodity $commodity, $date): ?Item
    {
        $commodityKey = $commodity->getId();
        $dateKey = Item::convertDateTimeToDateKey($date);

        return $this->items[$commodityKey][$dateKey] ?? null;
    }

    /**
     * @return \DateTime[]
     */
    public function getDates(): array
    {
        return $this->dates;
    }
}
