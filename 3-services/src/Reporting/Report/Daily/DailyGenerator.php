<?php

namespace App\Reporting\Report\Daily;

use App\Entity\WorldPrice\Commodity;
use App\Entity\WorldPrice\Item;
use App\Reporting\Exception\UnsupportedReportSourceException;
use App\Reporting\Generated\Cell;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Generator\Generator;
use App\Reporting\Renderer;
use App\Reporting\Source\ReportSource;

class DailyGenerator implements Generator
{
    public function getSourceProviderClass(): string
    {
        return DailyReportSourceProvider::class;
    }

    public function getStylerClasses(): array
    {
        return [
            Renderer::FORMAT_XLSX => DailySpreadsheetStyler::class,
            Renderer::FORMAT_HTML => DailyCssStyler::class,
        ];
    }

    public function generate(ReportSource $reportSource): GeneratedReport
    {
        if (!$reportSource instanceof DailyReportSource) {
            throw new UnsupportedReportSourceException(DailyReportSource::class, $reportSource);
        }

        $generatedReport = new GeneratedReport();
        // Daily
        $generatedReport->createAndAddRow(
            [
                new Cell('Daily', DailyCellPurpose::TABLE_CAPTION),
            ]
        );

        $isEmptyReport = true;
        foreach ($reportSource->getCommoditiesStructure() as $groupName => $commodities) {
            $line1 = $generatedReport->createRow();
            $line1->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            foreach ($reportSource->getDates() as $i => $date) {
                $line1->createAndAddCell($this->getDayName($date), DailyCellPurpose::COLUMN_CAPTION);
            }
            $line1->createAndAddCell('дневн.', DailyCellPurpose::COLUMN_CAPTION);
            $line1->createAndAddCell('дневн.', DailyCellPurpose::COLUMN_CAPTION);
            $generatedReport->addRow($line1);

            $line2 = $generatedReport->createRow();
            $line2->createAndAddCell($groupName, DailyCellPurpose::COLUMN_CAPTION);
            $line2->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            $line2->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            $line2->createAndAddCell('', DailyCellPurpose::COLUMN_CAPTION);
            foreach ($reportSource->getDates() as $i => $date) {
                $line2->createAndAddCell($date->format('d.m.y'), DailyCellPurpose::COLUMN_CAPTION);
            }
            $line2->createAndAddCell("изм.,\nUSD/т", DailyCellPurpose::COLUMN_CAPTION);
            $line2->createAndAddCell('изм.,        %', DailyCellPurpose::COLUMN_CAPTION);
            $generatedReport->addRow($line2);

            if (isset($commodities[Commodity::FUTURE])) {
                $this->buildLineCaptionFuture($generatedReport);
                $isFutureEmpty = $this->buildCommodities(
                    $reportSource,
                    $generatedReport,
                    $commodities[Commodity::FUTURE]
                );
            }

            if (isset($commodities[Commodity::CASH])) {
                $this->buildLineCaptionCash($generatedReport);
                $isCashEmpty = $this->buildCommodities($reportSource, $generatedReport, $commodities[Commodity::CASH]);
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
     * @param \DateTime $date
     * @return string
     */
    private function getDayName(\DateTime $date)
    {
        $weekDays = [
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
            7 => 'Вс',
        ];

        return $weekDays[$date->format('N')];
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
        $lineCaption->createAndAddCell('Фьючерсный рынок', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('биржа/ месяц', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('страна-производит.', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('условие поставки', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $generatedReport->addRow($lineCaption);
    }

    private function buildLineCaptionCash(GeneratedReport $generatedReport)
    {
        $lineCaption = $generatedReport->createRow();
        $generatedReport->addRow($lineCaption);
        $lineCaption->createAndAddCell('Наличный рынок', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('месяц', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('страна-производит.', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('условие поставки', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
        $lineCaption->createAndAddCell('', DailyCellPurpose::COLUMN_SUB_CAPTION);
    }

    /**
     * @param Commodity[] $commodities
     */
    private function buildCommodities(
        DailyReportSource $reportSource,
        GeneratedReport $generatedReport,
        array $commodities
    ) {
        $i = 1;
        $reportIsEmpty = true;
        foreach ($commodities as $commodity) {
            $evenRow = $i % 2 === 0;
            $purpose = $evenRow ? DailyCellPurpose::SIMPLE_EVEN : DailyCellPurpose::SIMPLE_ODD;
            $purposeChange = $evenRow ? DailyCellPurpose::CHANGE_EVEN : DailyCellPurpose::CHANGE_ODD;
            $purposeChangePositive = $evenRow ? DailyCellPurpose::CHANGE_POSITIVE_EVEN : DailyCellPurpose::CHANGE_POSITIVE_ODD;
            $purposeChangeNegative = $evenRow ? DailyCellPurpose::CHANGE_NEGATIVE_EVEN : DailyCellPurpose::CHANGE_NEGATIVE_ODD;

            $row = $generatedReport->createRow();
            $generatedReport->addRow($row);
            $row->createAndAddCell($commodity->getName(), $purpose);
            //FIXME: откуда берется дек. ? CBOT беру из колонки birg 'CBOT, дек.'
            $row->createAndAddCell($commodity->getExchanges(), $purpose);
            $row->createAndAddCell($commodity->getCountryName(), $purpose);
            $row->createAndAddCell($commodity->getDeliveryDescription(), $purpose);
            $firstItem = 0;
            $secondItem = 0;
            foreach ($reportSource->getDates() as $k => $date) {
                $item = $reportSource->getItemByCommodityAndDate($commodity, $date) ?: new Item();
                if ($k == 0) {
                    $firstItem = $item;
                }
                if ($k == 1) {
                    $secondItem = $item;
                }
                $price = '-';
                if ($item->getPrice()) {
                    $price = round($item->getPrice(), 1);
                    $reportIsEmpty = false;
                }

                $row->createAndAddCell($price, $purpose);
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
            //FIXME: если строка полностью пустая - нужно пропускать ее


            $i++;
        }

        return $reportIsEmpty;
    }
}
