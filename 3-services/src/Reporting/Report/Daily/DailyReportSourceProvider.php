<?php

namespace App\Reporting\Report\Daily;

use App\Entity\Price\Report;
use App\Entity\WorldPrice\Commodity;
use App\Reporting\Command\GenerationRequest;
use App\Reporting\Exception\UnsupportedGenerationRequestException;
use App\Reporting\Source\ReportSource;
use App\Reporting\Source\ReportSourceProvider;
use App\Repository\WorldPrice\ItemRepository;
use App\Repository\WorldPrice\CommodityRepository;

class DailyReportSourceProvider implements ReportSourceProvider
{
    private const WEEKEND_DAYS = [6, 7];
    private const DATES_COUNT_BY_DAYS = 6;

    private $commoditiesRepository;

    private $itemRepository;

    public function __construct(CommodityRepository $commoditiesRepository, ItemRepository $itemRepository)
    {
        $this->commoditiesRepository = $commoditiesRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getReportSource(Report $report, GenerationRequest $request): ReportSource
    {
        if (!$request instanceof DailyGenerationRequest) {
            throw new UnsupportedGenerationRequestException(DailyGenerationRequest::class, $request);
        }

        $from = $request->getDate();

        $dateToModify = clone $from;
        $firstDay = $from;
        if (in_array($from->format('N'), self::WEEKEND_DAYS)) {
            $firstDay = $dateToModify->modify('last friday');
            $dates[] = $firstDay;
        } else {
            $dates[] = $from;
        }

        $i = 1;
        do {
            $date = clone $firstDay;
            $prevDay = $date->modify('-'.$i.'days');

            $i++;
            if (in_array($prevDay->format('N'), self::WEEKEND_DAYS)) {
                continue;
            }
            $dates[] = $prevDay;
        } while (count($dates) < self::DATES_COUNT_BY_DAYS);

        $dates = array_reverse($dates);
        $commoditiesIdsByGroups = Commodity::COMMODITIES_BY_GROUPS;
        $commoditiesIds = Commodity::getAllCommoditiesIds();

        $commodities = $this->commoditiesRepository->getCommoditiesByIds($commoditiesIds);
        $commoditiesStructure = [];
        foreach ($commoditiesIdsByGroups as $groupName => $ids) {
            foreach ($ids as $id) {
                $commodity = isset($commodities[$id]) ? $commodities[$id] : null;
                if (!$commodity) {
                    continue;
                }
                if (!$commodity->isDaily()) {
                    continue;
                }
                $commoditiesStructure[$groupName][(int)$commodity->isCash()][] = $commodity;
            }
        }

        $items = $this->itemRepository->getItemsForCommoditiesByDates($commodities, $dates);
        $itemsStructure = [];
        foreach ($items as $item) {
            $commodityKey = $item->getCommodity()->getId();
            $dateKey = $item->getDateKey();
            $itemsStructure[$commodityKey][$dateKey] = $item;
        }

        return new DailyReportSource(
            $report,
            [],
            $from,
            $commoditiesStructure,
            $itemsStructure,
            $dates
        );
    }
}

