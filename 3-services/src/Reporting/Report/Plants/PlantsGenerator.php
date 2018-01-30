<?php

namespace App\Reporting\Report\Plants;

use App\Entity\Price\Item;
use App\Reporting\Generated\Cell;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Generator\Exception\UnsupportedReportSourceException;
use App\Reporting\Generator\Generator;
use App\Reporting\Renderer;
use App\Reporting\Source\ReportSource;

class PlantsGenerator implements Generator
{
    public function getSourceProviderClass(): string
    {
        return PlantsReportSourceProvider::class;
    }

    public function getStylerClasses(): array
    {
        return [
            Renderer::FORMAT_XLSX => PlantsSpreadsheetStyler::class,
            Renderer::FORMAT_HTML => PlantsCssStyler::class,
        ];
    }

    public function generate(ReportSource $reportSource): GeneratedReport
    {
        if (!$reportSource instanceof PlantsReportSource) {
            throw new UnsupportedReportSourceException(PlantsReportSource::class, $reportSource);
        }

        $generatedReport = new GeneratedReport();
        // Картофель в/с
        $generatedReport->createAndAddRow(
            [
                new Cell($reportSource->getReport()->getName(), PlantsCellPurpose::TABLE_CAPTION, 15),
            ]
        );

        $line1 = $generatedReport->createRow();
        $line2 = $generatedReport->createRow();

        $generatedReport->addRow($line1);
        $generatedReport->addRow($line2);

        $line1->createAndAddCell('Рынок', PlantsCellPurpose::COLUMN_CAPTION, 1, 2);

        foreach ($reportSource->getDates() as $i => $date) {
            $dayName = $this->getDayName($date);
            if ($i <= 4) {
                $line1->createAndAddCell($dayName.' '.$date->format('m.d'), PlantsCellPurpose::COLUMN_CAPTION, 1, 2);
            } elseif ($i == 5) {
                $line1->createAndAddCell($dayName.' '.$date->format('m.d'), PlantsCellPurpose::COLUMN_CAPTION, 3);
                $line2->createAndAddCell('сред.', PlantsCellPurpose::COLUMN_CAPTION);
                $line2->createAndAddCell('мин.', PlantsCellPurpose::COLUMN_CAPTION);
                $line2->createAndAddCell('макс.', PlantsCellPurpose::COLUMN_CAPTION);
            } else {
                $line1->createAndAddCell('Изменение, %', PlantsCellPurpose::COLUMN_CAPTION, 6);
                $line2->createAndAddCell('нед.', PlantsCellPurpose::COLUMN_CAPTION, 2);
                $line2->createAndAddCell('мес.', PlantsCellPurpose::COLUMN_CAPTION, 2);
                $line2->createAndAddCell('год', PlantsCellPurpose::COLUMN_CAPTION, 2);
                break;
            }
        }

        foreach ($reportSource->getLines() as $line) {
            $row = $generatedReport->createRow();
            $generatedReport->addRow($row);

            $commodity = $line->getCommodity();
            $row->createAndAddCell($commodity->getName(), PlantsCellPurpose::ROW_CAPTION);
            foreach ($reportSource->getDates() as $i => $date) {
                $item = $reportSource->getItemByCommodity($commodity, $date) ?: new Item();
                if ($i <= 4) {
                    $row->createAndAddCell($item->getAvgPrice());
                } elseif ($i == 5) {
                    $row->createAndAddCell($item->getAvgPrice());
                    $row->createAndAddCell($item->getMinPrice());
                    $row->createAndAddCell($item->getMaxPrice());
                } else {
                    $baseDate = $reportSource->getBaseDate();
                    $baseItem = $reportSource->getItemByCommodity($commodity, $baseDate) ?: new Item();

                    $avg = $baseItem->getAvgPrice();
                    if ($avg) {
                        $diff = $item->getAvgPrice() - $baseItem->getAvgPrice();
                        $symbol = ' ';
                        if ($diff > 0) {
                            $symbol = '▲';
                            $row->createAndAddCell($symbol, PlantsCellPurpose::SYMBOL_UP);
                        } elseif ($diff < 0) {
                            $symbol = '▼';
                            $row->createAndAddCell($symbol, PlantsCellPurpose::SYMBOL_DOWN);
                        } else {
                            $row->createAndAddCell($symbol, PlantsCellPurpose::SYMBOL_DOWN);
                        }

                        $row->createAndAddCell($diff * 100 / 1, PlantsCellPurpose::CHANGE);
                    } else {
                        $row->createAndAddCell('', PlantsCellPurpose::SYMBOL_DOWN);
                        $row->createAndAddCell(0, PlantsCellPurpose::CHANGE);
                    }
                }
            }
        }

        return $generatedReport;
    }

    private function getDayName(\DateTime $date)
    {
        $weekDays = [
            1 => 'Пн.',
            2 => 'Вт.',
            3 => 'Ср.',
            4 => 'Чт.',
            5 => 'Пт.',
            6 => 'Сб.',
            7 => 'Вс.',
        ];

        return $weekDays[$date->format('N')];
    }
}
