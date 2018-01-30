<?php

namespace App\Reporting\Report\Weekly;

use App\Entity\Price\Report;
use App\Entity\WorldPrice\Commodity;
use App\Reporting\Command\GenerationRequest;
use App\Reporting\Exception\UnsupportedGenerationRequestException;
use App\Reporting\Source\ReportSource;
use App\Reporting\Source\ReportSourceProvider;
use App\Repository\WorldPrice\ItemRepository;
use App\Repository\WorldPrice\CommodityRepository;

class WeeklyReportSourceProvider implements ReportSourceProvider
{
    private const FRIDAY = 5;
    private const DATES_COUNT_BY_WEEKS = 6;

    private $commoditiesRepository;

    private $itemRepository;

    public function __construct(CommodityRepository $commoditiesRepository, ItemRepository $itemRepository)
    {
        $this->commoditiesRepository = $commoditiesRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getReportSource(Report $report, GenerationRequest $request): ReportSource
    {
        if (!$request instanceof WeeklyGenerationRequest) {
            throw new UnsupportedGenerationRequestException(WeeklyGenerationRequest::class, $request);
        }

        $from = $request->getDate();

        $dateToModify = clone $from;
        $dates[] = $lastFriday = $from->format('N') === self::FRIDAY ? $from : $dateToModify->modify('last friday');
        $i = 1;
        do {
            $date = clone $lastFriday;
            $prevWeek = $date->modify('-'.$i.'weeks');
            $i++;
            $dates[] = $prevWeek;
        } while (count($dates) < self::DATES_COUNT_BY_WEEKS);

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
                if ($commodity->isDaily()) {
                    continue;
                }
                $commoditiesStructure[$groupName][(int)$commodity->isCash()][] = $commodity;
            }
        }

        $itemsByWeek = $this->itemRepository->getItemsForCommoditiesByInterval($commodities, $dates[5], $dates[0]);
//        $items = $this->itemRepository->getItemsForCommoditiesByDates($commodities, $dates);

        $itemsStructure = [];
        $dateKeys = $this->convertDatesToDateKeys($dates);
//                    dump($dateKeys ,$itemsByWeek);
//            exit();
        foreach ($itemsByWeek as $week => $itemsByCommodity) {
//            dump($dateKeys ,$week, $items);
//            exit();
            foreach ($itemsByCommodity as $commodityKey => $items) {
                foreach ($items as $item) {
                    $dateKey = $item->getDateKey();

                    if ($dateKeys[$week] == $item->getDateKey() && $item->getPrice()) {

                        $itemsStructure[$commodityKey][$dateKey] = $item;
                        break;
                    } elseif ($item->getPrice()) {
//                        dump( $item->getDateKey(),$item->getPrice());
//                        exit();

                        $itemsStructure[$commodityKey][$dateKey] = $item;
                    }
                }
            }

        }

//        dump($itemsStructure);
//        exit();

        return new WeeklyReportSource(
            $report,
            [],
            $from,
            $commoditiesStructure,
            $itemsStructure,
            $dates,
            $dateKeys
        );
    }

    /**
     * @param \DateTime[] $dates
     */
    private function convertDatesToDateKeys(array $dates)
    {
        $dateKeys = [];
        foreach ($dates as $date) {
            $dateKeys[$date->format('W')] = $date->format('Y-m-d');
        }

        return $dateKeys;
    }
}
