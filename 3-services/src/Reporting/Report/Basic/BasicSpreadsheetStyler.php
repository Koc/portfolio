<?php

namespace App\Reporting\Report\Basic;

use App\Reporting\Generated\Cell;
use App\Reporting\Generated\CellPurpose;
use App\Reporting\Renderer\SpreadsheetStyler;
use App\Reporting\Renderer\SpreadsheetStylerUtil;
use PhpOffice\PhpSpreadsheet\Cell\Cell as ExcelCell;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BasicSpreadsheetStyler implements SpreadsheetStyler
{
    public function applySheetStyling(Worksheet $worksheet): void
    {
        //FIXME: на ubuntu отображается как оигинал, на windows нет
        $worksheet->getColumnDimension('B')->setWidth("17.73");
        $worksheet->getColumnDimension('C')->setWidth("13.2788");
        $worksheet->getColumnDimension('D')->setWidth("8.9");
        $worksheet->getColumnDimension('E')->setWidth("8.9");
        $worksheet->getColumnDimension('F')->setWidth("8.9");
        $worksheet->getColumnDimension('G')->setWidth("8.9");
        $worksheet->getColumnDimension('H')->setWidth("8.9");
        $worksheet->getColumnDimension('I')->setWidth("8.9");
    }

    public function applyCellStyling(ExcelCell $excelCell, Cell $cell): void
    {
        $styles = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size' => 10,
                'name' => 'Arial',
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_TOP,
            ],
        ];

        switch ($cell->getPurpose()) {
            case CellPurpose::ROW_CAPTION:
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_LEFT;
                break;

            case CellPurpose::TABLE_CAPTION:
                $styles['font']['size'] = 12;
                $styles['font']['bold'] = true;

                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '888888',
                    ],
                ];
                break;

            case CellPurpose::COLUMN_CAPTION:
                $styles['font']['bold'] = true;
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '888888',
                    ],
                ];
                break;
        }

        SpreadsheetStylerUtil::applyStylesFromArray($excelCell, $styles);
    }
}
