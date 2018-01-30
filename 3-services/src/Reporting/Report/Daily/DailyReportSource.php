<?php

namespace App\Reporting\Report\Daily;

use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use App\Entity\WorldPrice\Commodity;
use App\Entity\WorldPrice\Item;
use App\Reporting\Source\ReportSource;

class DailyReportSource extends ReportSource
{
    private $baseDate;

    /**
     * @var Item[]|array<Commodity.id, array<Item.getDateKey, Item>>
     */
    private $items;

    /**
     * @var array|array<group.name, array<isCash, Commodity>>
     */
    private $commoditiesStructure;

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
        array $commoditiesStructure,
        array $items,
        array $dates
    ) {
        parent::__construct($report, $lines);

        $this->baseDate = $baseDate;
        $this->items = $items;
        $this->commoditiesStructure = $commoditiesStructure;
        $this->dates = $dates;
    }

    /**
     * @return \DateTime
     */
    public function getBaseDate(): \DateTime
    {
        return $this->baseDate;
    }

    public function getItemByCommodityAndDate(Commodity $commodity, $date): ?Item
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

    /**
     * @return array
     */
    public function getCommoditiesStructure(): array
    {
        return $this->commoditiesStructure;
    }
}
