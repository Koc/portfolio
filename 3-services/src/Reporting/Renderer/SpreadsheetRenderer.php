<?php

namespace App\Reporting\Renderer;

use App\Reporting\Generated\Cell;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Renderer;
use App\Reporting\Renderer\MatrixTraverser\ReportMatrixTraveller;
use App\Reporting\ReportResponse;
use App\Reporting\ReportsStorage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetRenderer implements Renderer
{
    private const START_COLUMN = 1;

    private const TOP_MARGIN = 2;

    private $storage;

    private $stylerFactory;

    public function __construct(ReportsStorage $storage, SpreadsheetStylerFactory $stylerFactory)
    {
        $this->storage = $storage;
        $this->stylerFactory = $stylerFactory;
    }

    /**
     * @param GeneratedReport[] $reports
     * @param string[] $stylerClasses
     *
     * @return ReportResponse
     */
    public function renderGeneratedReportCollection(array $reports, array $stylerClasses): ReportResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // относительно этой строки рендерится отчет
        $reportBaseLine = 1;
        foreach ($reports as $i => $report) {
            $styler = $this->stylerFactory->getStyler($stylerClasses[$i]);

            // sheet styles applyed only once per sheet
            if ($styler && $i === 0) {
                $styler->applySheetStyling($sheet);
            }

            $lastUsedLine = $reportBaseLine;
            $reportBaseLine += self::TOP_MARGIN;
            $traveller = new ReportMatrixTraveller($report);

            foreach ($traveller as $iteration) {
                $column = self::START_COLUMN + $iteration->getColumn();
                $line = $reportBaseLine + $iteration->getLine();
                $lastUsedLine = $line;
                $cell = $iteration->getCell();

                $excelCell = $sheet->getCellByColumnAndRow($column, $line);
                $excelCell->setValue($cell->getValue());

                if (null !== $cell->getNote()) {
                    $sheet
                        ->getCommentByColumnAndRow($column, $line)
                        ->getText()
                        ->createTextRun($cell->getNote());
                }

                $this->applyCellMerging($sheet, $cell, $column, $line);

                $styler->applyCellStyling($excelCell, $cell);
            }
            $reportBaseLine = $lastUsedLine + 1;
        }

        $filename = $this->storage->generateTargetPath(self::getFormat());
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        return new ReportResponse($filename);
    }

    public static function getFormat(): string
    {
        return 'xlsx';
    }

    private function applyCellMerging(Worksheet $worksheet, Cell $cell, $column, $line): void
    {
        if (!$cell->requiresMerge()) {
            return;
        }

        $worksheet
            ->mergeCellsByColumnAndRow(
                $column,
                $line,
                $column + $cell->getColspan() - 1,
                $line + $cell->getRowspan() - 1
            );
    }
}
