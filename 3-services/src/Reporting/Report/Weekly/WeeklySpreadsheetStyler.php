<?php

namespace App\Reporting\Report\Weekly;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\SpreadsheetStyler;
use App\Reporting\Renderer\SpreadsheetStylerUtil;
use PhpOffice\PhpSpreadsheet\Cell\Cell as ExcelCell;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeeklySpreadsheetStyler implements SpreadsheetStyler
{
    public function applySheetStyling(Worksheet $worksheet): void
    {
        //FIXME: UBUNTU XLSX ширина столбцов в cм показывается, но измеряется в своих единицах
        $worksheet->getColumnDimension('A')->setWidth("13.75");
        $worksheet->getColumnDimension('B')->setWidth("7.2");
        $worksheet->getColumnDimension('C')->setWidth("9.5");
        $worksheet->getColumnDimension('D')->setWidth("9.41");
        $worksheet->getColumnDimension('E')->setWidth("5.25");
        $worksheet->getColumnDimension('F')->setWidth("5.25");
        $worksheet->getColumnDimension('G')->setWidth("5.25");
        $worksheet->getColumnDimension('H')->setWidth("5.25");
        $worksheet->getColumnDimension('I')->setWidth("5.25");
        $worksheet->getColumnDimension('J')->setWidth("5.25");
        $worksheet->getColumnDimension('K')->setWidth("4.5");
        $worksheet->getColumnDimension('L')->setWidth("4.5");
//        //FIXME: высота 1 = 0.04см
//        $worksheet->getRowDimension(4)->setRowHeight("28.25");

    }

    public function applyCellStyling(ExcelCell $excelCell, Cell $cell): void
    {
        //FIXME: смотри DailySpreadsheetStyler и делай как там

        $styles = [
            'font' => [
                'bold' => false,
                'name' => 'Arial Cyr',
                'size' => '8',
                'color' => ['rgb' => '130000'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Style\Border::BORDER_THIN,
                ],
            ],
        ];
        switch ($cell->getPurpose()) {
            case WeeklyCellPurpose::COLUMN_CAPTION:
                $styles['alignment']['wrapText'] = true;
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_CENTER;
                $styles['alignment']['vertical'] = Style\Alignment::VERTICAL_CENTER;
                $styles['font']['bold'] = true;
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'C0C0C0',
                    ],
                    'endColor' => [
                        'rgb' => 'C0C0C0',
                    ],
                ];
                break;
            case WeeklyCellPurpose::ROW_CAPTION:
                $styles['alignment']['wrapText'] = true;
                $styles['font']['bold'] = true;
                break;

            case WeeklyCellPurpose::SIMPLE:

                break;

            case WeeklyCellPurpose::CHANGE_ODD:
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_RIGHT;
                break;

            case WeeklyCellPurpose::CHANGE_POSITIVE_ODD:
                $styles['font']['color'] = ['rgb' => '008000'];
                break;

            case WeeklyCellPurpose::CHANGE_NEGATIVE_ODD:
                $styles['font']['color'] = ['rgb' => '7F0028'];
                break;

            case WeeklyCellPurpose::TABLE_CAPTION:
                $styles['font']['bold'] = true;
                $styles['font']['size'] = 14;
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'FFCC99',
                    ],
                    'endColor' => [
                        'rgb' => 'FFCC99',
                    ],
                ];
                break;
        }

        SpreadsheetStylerUtil::applyNoteStyle($excelCell, ['bold' => true]);
        SpreadsheetStylerUtil::applyStylesFromArray($excelCell, $styles);
    }
}
