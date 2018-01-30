<?php

namespace App\Reporting\Report\Basic;

use App\Entity\Price\Commodity;
use App\Entity\Price\Item;
use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use App\Reporting\Source\ReportSource;

class BasicReportSource extends ReportSource
{
    /**
     * @var Item[]|array<Commodity.id, array<Item.getDateKey, Item>>
     */
    private $items;

    private $baseDate;

    private $actualDate;

    /**
     * @param ReportLine[] $lines
     * @var Item[]|array<Commodity.id, array<Item.getDateKey, Item>>
     */
    public function __construct(
        Report $report,
        array $lines,
        array $items,
        \DateTime $baseDate,
        \DateTime $actualDate
    ) {
        parent::__construct($report, $lines);

        $this->items = $items;
        $this->baseDate = $baseDate;
        $this->actualDate = $actualDate;
    }

    public function getBaseItemByCommodity(Commodity $commodity): ?Item
    {
        return $this->getItemByCommodityAndDate($commodity, $this->baseDate);
    }

    public function getActualItemByCommodity(Commodity $commodity): ?Item
    {
        return $this->getItemByCommodityAndDate($commodity, $this->actualDate);
    }

    private function getItemByCommodityAndDate(Commodity $commodity, $date): ?Item
    {
        $commodityKey = $commodity->getId();
        $dateKey = Item::convertDateTimeToDateKey($date);

        return $this->items[$commodityKey][$dateKey] ?? null;
    }

    /**
     * @return \DateTime
     */
    public function getBaseDate(): \DateTime
    {
        return $this->baseDate;
    }
}
