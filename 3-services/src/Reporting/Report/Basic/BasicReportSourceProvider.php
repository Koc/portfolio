<?php

namespace App\Reporting\Report\Basic;

use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use App\Reporting\Command\GenerationRequest;
use App\Reporting\Source\Exception\UnsupportedGenerationRequestException;
use App\Reporting\Source\ReportSource;
use App\Reporting\Source\ReportSourceProvider;
use App\Repository\Price\ItemRepository;
use App\Repository\Price\ReportLineRepository;

class BasicReportSourceProvider implements ReportSourceProvider
{
    private $reportLineRepository;

    private $itemRepository;

    public function __construct(ReportLineRepository $reportLineRepository, ItemRepository $itemRepository)
    {
        $this->reportLineRepository = $reportLineRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getReportSource(Report $report, GenerationRequest $request): ReportSource
    {
        if (!$request instanceof BasicGenerationRequest) {
            throw new UnsupportedGenerationRequestException(BasicGenerationRequest::class, $request);
        }

        $from = $request->getDateFrom();
        $to = $request->getDateTo();
        $lines = $this->reportLineRepository->getLinesForReport($report);

        $commodities = array_map(
            function (ReportLine $reportLine) {
                return $reportLine->getCommodity();
            },
            $lines
        );

        $items = $this->itemRepository->getItemsForCommoditiesByDates($commodities, [$from, $to]);

        $itemsStructure = [];
        foreach ($items as $item) {
            $commodityKey = $item->getCommodity()->getId();
            $dateKey = $item->getDateKey();
            $itemsStructure[$commodityKey][$dateKey] = $item;
        }

        return new BasicReportSource($report, $lines, $itemsStructure, $from, $to);
    }
}
