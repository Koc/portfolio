<?php

namespace App\Reporting\Report\Weekly;

use App\Entity\WorldPrice\Commodity;
use App\Entity\WorldPrice\Item;
use App\Reporting\Exception\UnsupportedReportSourceException;
use App\Reporting\Generated\Cell;
use App\Reporting\Generated\CellPurpose;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Generator\Generator;
use App\Reporting\Renderer;
use App\Reporting\Report\Daily\DailyCellPurpose;
use App\Reporting\Source\ReportSource;

class WeeklyGenerator implements Generator
{
    public static function getShortAlias(): string
    {
        return 'WeeklyGenerator';
    }

    public function getGenerationRequestClass(): string
    {
        return WeeklyGenerationRequest::class;
    }

    public function getSourceProviderClass(): string
    {
        return WeeklyReportSourceProvider::class;
    }

    public function getStylerClasses(): array
    {
        return [
            Renderer::FORMAT_XLSX => WeeklySpreadsheetStyler::class,
            Renderer::FORMAT_HTML => WeeklyCssStyler::class,
        ];
    }

    public function generate(ReportSource $reportSource): GeneratedReport
    {
        if (!$reportSource instanceof WeeklyReportSource) {
            throw new UnsupportedReportSourceException(WeeklyReportSource::class, $reportSource);
        }

        $generatedReport = new GeneratedReport();
        $generatedReport->createAndAddRow(
            [
                new Cell('Weekly', CellPurpose::TABLE_CAPTION),
            ]
        );

        $dateKeys = $dateKeys = $reportSource->getDateKeys();
        $isEmptyReport = true;

        foreach ($reportSource->getCommoditiesStructure() as $groupName => $commodities) {
            $line1 = $generatedReport->createRow();

            $line1->createAndAddCell($groupName, CellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell(' ', CellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell(' ', CellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell(' ', CellPurpose::COLUMN_CAPTION);
            foreach ($reportSource->getDates() as $i => $date) {
                $line1->createAndAddCell($date->format('d.m.y'), CellPurpose::COLUMN_CAPTION);
            }
            $line1->createAndAddCell("недел.\nизм.,\nUSD/т", CellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell('недел. изм.,        %', CellPurpose::COLUMN_CAPTION);
            $generatedReport->addRow($line1);

            if (isset($commodities[Commodity::FUTURE])) {
                $this->buildLineCaptionFuture($generatedReport);
                $isFutureEmpty = $this->buildCommodities(
                    $reportSource,
                    $generatedReport,
                    $commodities[Commodity::FUTURE],
                    $dateKeys
                );
            }

            if (isset($commodities[Commodity::CASH])) {
                $this->buildLineCaptionCash($generatedReport);
                $isCashEmpty = $this->buildCommodities(
                    $reportSource,
                    $generatedReport,
                    $commodities[Commodity::CASH],
                    $dateKeys
                );
            }

            if (!$isFutureEmpty || !$isCashEmpty) {
                $isEmptyReport = false;
            }
        }

        if ($isEmptyReport) {
            return new GeneratedReport();
        }

        return $generatedReport;
    }

    /**
     * @param Item $firstItem
     * @param Item $secondItem
     * @return array
     */
    private function getCalculateResults(Item $firstItem, Item $secondItem)
    {
        $result = 0;
        $resultByDiv = 0;
        if ($firstItem->getPrice() && $secondItem->getPrice()) {
            $result = $firstItem->getPrice() - $secondItem->getPrice();
            if ($result !== 0) {
                $resultByDiv = ($result / $secondItem->getPrice()) * 100;
            }
        }

        return [$result, $resultByDiv];
    }

    private function buildLineCaptionFuture(GeneratedReport $generatedReport)
    {
        $lineCaption = $generatedReport->createRow();
        $lineCaption->createAndAddCell('Фьючерсный рынок', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('биржа/ месяц', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('страна-производит.', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('условие поставки', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $generatedReport->addRow($lineCaption);
    }

    private function buildLineCaptionCash(GeneratedReport $generatedReport)
    {
        $lineCaption = $generatedReport->createRow();
        $generatedReport->addRow($lineCaption);
        $lineCaption->createAndAddCell('Наличный рынок', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('месяц', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('страна-производит.', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('условие поставки', CellPurpose::ROW_CAPTION);
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
        $lineCaption->createAndAddCell('');
    }

    /**
     * @param Commodity[] $commodities
     * @param string[] $dateKeys
     */
    private function buildCommodities(
        WeeklyReportSource $reportSource,
        GeneratedReport $generatedReport,
        array $commodities,
        array $dateKeys
    ) {
        $i = 1;
        $reportIsEmpty = true;
        foreach ($commodities as $commodity) {
            $evenRow = $i % 2 === 0;
            $purpose = $evenRow ? DailyCellPurpose::SIMPLE_EVEN : DailyCellPurpose::SIMPLE_ODD;
            $purposeChange = $evenRow ? DailyCellPurpose::CHANGE_EVEN : DailyCellPurpose::CHANGE_ODD;
            $purposeChangePositive = $evenRow ? DailyCellPurpose::CHANGE_POSITIVE_EVEN : DailyCellPurpose::CHANGE_POSITIVE_ODD;
            $purposeChangeNegative = $evenRow ? DailyCellPurpose::CHANGE_NEGATIVE_EVEN : DailyCellPurpose::CHANGE_NEGATIVE_ODD;

            $rowIsEmpty = true;
            $row = $generatedReport->createRow();
            $row->createAndAddCell($commodity->getName().' '.$commodity->getId(), $purpose);
            //FIXME: откуда берется дек. ? CBOT беру из колонки birg 'CBOT, дек.'
            $row->createAndAddCell($commodity->getExchanges(), $purpose);
            $row->createAndAddCell($commodity->getCountryName(), $purpose);
            $row->createAndAddCell($commodity->getDeliveryDescription(), $purpose);
            $firstItem = 0;
            $secondItem = 0;
            foreach ($reportSource->getDates() as $i => $date) {
                $item = $reportSource->getItemByCommodityAndDate($commodity, $date) ?: new Item();
                if ($i == 0) {
                    $firstItem = $item;
                }
                if ($i == 1) {
                    $secondItem = $item;
                }
                $price = '-';
                if ($item->getPrice()) {
                    $price = round($item->getPrice(), 1);
                    $rowIsEmpty = false;
                }
                $priceCell = $row->createCell($price);
                if ($item->getPublishedAt() && !in_array($item->getDateKey(), $dateKeys)) {
                    $priceCell->setNote($item->getPublishedAt()->format('d.m.y'));
                }

                $row->addCell($priceCell);
            }

            list($result, $resultByDiv) = $this->getCalculateResults($firstItem, $secondItem);
            if ($result > 0) {
                $row->createAndAddCell(round($result, 1), $purposeChangePositive);
                $row->createAndAddCell(round($resultByDiv, 1), $purposeChangePositive);
            } elseif ($result < 0) {
                $row->createAndAddCell(round($result, 1), $purposeChangeNegative);
                $row->createAndAddCell(round($resultByDiv, 1).'%', $purposeChangeNegative);
            } else {
                $row->createAndAddCell('-', $purposeChange);
                $row->createAndAddCell('-', $purposeChange);
            }

            if (!$rowIsEmpty) {
                $generatedReport->addRow($row);
                $reportIsEmpty = false;
            }
        }

        return $reportIsEmpty;
    }
}
