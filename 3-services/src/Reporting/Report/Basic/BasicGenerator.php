<?php

namespace App\Reporting\Report\Basic;

use App\Entity\Price\Item;
use App\Reporting\Generated\Cell;
use App\Reporting\Generated\CellPurpose;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Generator\Exception\UnsupportedReportSourceException;
use App\Reporting\Generator\Generator;
use App\Reporting\Renderer;
use App\Reporting\Source\ReportSource;

class BasicGenerator implements Generator
{
    public static function getShortAlias(): string
    {
        return 'BasicGenerator';
    }

    public function getGenerationRequestClass(): string
    {
        return BasicGenerationRequest::class;
    }

    public function getSourceProviderClass(): string
    {
        return BasicReportSourceProvider::class;
    }

    public function getStylerClasses(): array
    {
        return [
            Renderer::FORMAT_XLSX => BasicSpreadsheetStyler::class,
            Renderer::FORMAT_HTML => BasicCssStyler::class,
        ];
    }

    public function generate(ReportSource $reportSource): GeneratedReport
    {
        if (!$reportSource instanceof BasicReportSource) {
            throw new UnsupportedReportSourceException(BasicReportSource::class, $reportSource);
        }

        $generatedReport = new GeneratedReport();

        // Динамика цен на продукты переработки зерновых в Украине (предложение, EXW), грн/т с НДС
        $generatedReport->createAndAddRow(
            [
                new Cell($reportSource->getReport()->getName(), CellPurpose::TABLE_CAPTION, 8),
            ]
        );

        // 		мин.	изм.	макс.	изм.	сред.	изм.
        $generatedReport->createAndAddRow(
            [
                new Cell(' ', CellPurpose::COLUMN_CAPTION, 2),
                new Cell('мин.', CellPurpose::COLUMN_CAPTION),
                new Cell('изм.', CellPurpose::COLUMN_CAPTION),
                new Cell('макс.', CellPurpose::COLUMN_CAPTION),
                new Cell('изм.', CellPurpose::COLUMN_CAPTION),
                new Cell('сред.', CellPurpose::COLUMN_CAPTION),
                new Cell('изм.', CellPurpose::COLUMN_CAPTION),
            ]
        );

        foreach ($reportSource->getLines() as $line) {
            $row = $generatedReport->createRow();
            $generatedReport->addRow($row);

            $commodity = $line->getCommodity();
            $baseItem = $reportSource->getBaseItemByCommodity($commodity) ?: new Item();
            $actualItem = $reportSource->getActualItemByCommodity($commodity)?: new Item();

            // Мука в/с
            $row->createAndAddCell($commodity->getName(), CellPurpose::ROW_CAPTION, 2);

            // Мука в/с - мин.
            $row->createAndAddCell($actualItem->getMinPrice());
            // Мука в/с - изм.
            $row->createAndAddCell($actualItem->getMinPrice() - $baseItem->getMinPrice());

            // Мука в/с - макс.
            $row->createAndAddCell($actualItem->getMaxPrice());
            // Мука в/с - изм.
            $row->createAndAddCell($actualItem->getMaxPrice() - $baseItem->getMaxPrice());

            // Мука в/с - сред.
            $row->createAndAddCell($actualItem->getAvgPrice());
            // Мука в/с - изм.
            $row->createAndAddCell($actualItem->getAvgPrice() - $baseItem->getAvgPrice());
        }

        return $generatedReport;
    }
}
