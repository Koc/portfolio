<?php

namespace App\Reporting\Report\Plants;

use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use App\Reporting\Command\GenerationRequest;
use App\Reporting\Source\Exception\UnsupportedGenerationRequestException;
use App\Reporting\Source\ReportSource;
use App\Reporting\Source\ReportSourceProvider;
use App\Repository\Price\ItemRepository;
use App\Repository\Price\ReportLineRepository;

class PlantsReportSourceProvider implements ReportSourceProvider
{
    private const WEEKEND_DAYS = [6, 7];
    private const FRIDAY = 5;
    private const DATES_COUNT_BY_DAYS = 6;

    private $reportLineRepository;

    private $itemRepository;

    public function __construct(ReportLineRepository $reportLineRepository, ItemRepository $itemRepository)
    {
        $this->reportLineRepository = $reportLineRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getReportSource(Report $report, GenerationRequest $request): ReportSource
    {
        if (!$request instanceof PlantsGenerationRequest) {
            throw new UnsupportedGenerationRequestException(PlantsGenerationRequest::class, $request);
        }

        $from = $request->getDate();

        $dateToModify = clone $from;
        $dates[] = $lastFriday = $from->format('N') === self::FRIDAY ? $from : $dateToModify->modify('last friday');
        $i = 1;
        do {
            $date = clone $lastFriday;
            $prevDay = $date->modify('-'.$i.'days');

            $i++;
            if (in_array($prevDay->format('N'), self::WEEKEND_DAYS)) {
                continue;
            }
            $dates[] = $prevDay;
        } while (count($dates) < self::DATES_COUNT_BY_DAYS);
        $dates = array_reverse($dates);
//        dump($dates);
//        exit();
        $lines = $this->reportLineRepository->getLinesForReport($report);

        $commodities = array_map(
            function (ReportLine $reportLine) {
                return $reportLine->getCommodity();
            },
            $lines
        );

        //http://apk-wm-api.dev/api/groups/13/2017-08-01/2017-10-22/xlsx/generate

        $dates[] = new \DateTime('last day of previous week');
        $dates[] = new \DateTime('last day of previous month');
        $dates[] = new \DateTime('last day of previous year');

        $items = $this->itemRepository->getItemsForCommoditiesByDates($commodities, $dates);

        $itemsStructure = [];
        foreach ($items as $item) {
            $commodityKey = $item->getCommodity()->getId();
            $dateKey = $item->getDateKey();
            $itemsStructure[$commodityKey][$dateKey] = $item;
        }

        return new PlantsReportSource($report, $lines, $from, $itemsStructure, $dates);
    }
}
