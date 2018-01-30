<?php

namespace App\Reporting\Report\Daily;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\SpreadsheetStyler;
use App\Reporting\Renderer\SpreadsheetStylerUtil;
use PhpOffice\PhpSpreadsheet\Cell\Cell as ExcelCell;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailySpreadsheetStyler implements SpreadsheetStyler
{
    public function applySheetStyling(Worksheet $worksheet): void
    {
        //FIXME: UBUNTU XLSX ширина столбцов в cм показывается, но измеряется в своих единицах
        $worksheet->getColumnDimension('A')->setWidth("14.58");
        $worksheet->getColumnDimension('B')->setWidth("7.58");
        $worksheet->getColumnDimension('C')->setWidth("8.625");
        $worksheet->getColumnDimension('D')->setWidth("9.8");
        $worksheet->getColumnDimension('E')->setWidth("5.58");
        $worksheet->getColumnDimension('F')->setWidth("5.58");
        $worksheet->getColumnDimension('G')->setWidth("5.58");
        $worksheet->getColumnDimension('H')->setWidth("5.58");
        $worksheet->getColumnDimension('I')->setWidth("5.58");
        $worksheet->getColumnDimension('J')->setWidth("5.58");
        $worksheet->getColumnDimension('K')->setWidth("4.875");
        $worksheet->getColumnDimension('L')->setWidth("4.875");
    }

    public function applyCellStyling(ExcelCell $excelCell, Cell $cell): void
    {
        $styles = [
            'font' => [
                'bold' => false,
                'name' => 'Arial Cyr',
                'size' => '8',
                'color' => ['rgb' => '130000'],
            ],
        ];

        switch ($cell->getPurpose()) {
            case DailyCellPurpose::TABLE_CAPTION:
                $styles['font']['bold'] = true;
                $styles['font']['size'] = 14;
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '00CCFF',
                    ],
                    'endColor' => [
                        'rgb' => '00CCFF',
                    ],
                ];
                break;

            case DailyCellPurpose::COLUMN_CAPTION:
                $styles['alignment']['wrapText'] = true;
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_CENTER;
                $styles['alignment']['vertical'] = Style\Alignment::VERTICAL_CENTER;
                $styles['font']['bold'] = true;
                $styles['font']['color'] = ['rgb' => 'FFFFF0'];
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '369684',
                    ],
                    'endColor' => [
                        'rgb' => '369684',
                    ],
                ];
                break;
            case DailyCellPurpose::COLUMN_SUB_CAPTION:
                $styles['alignment']['wrapText'] = true;
                $styles['font']['bold'] = true;
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'B3E9C2',
                    ],
                    'endColor' => [
                        'rgb' => 'B3E9C2',
                    ],
                ];
                break;


            case DailyCellPurpose::SIMPLE_EVEN:
                //FIXME: нужно сделать заливку сереньким для зебры
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'DDDDDD',
                    ],
//                    'endColor' => [
//                        'rgb' => 'DDDDDD',
//                    ],
                ];
                // break not needed

            case DailyCellPurpose::SIMPLE_ODD:
                $styles['borders']['allBorders'] = [
                    'borderStyle' => Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'C0C0C0',

                    ],
                ];

                break;

            case DailyCellPurpose::CHANGE_EVEN:
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'DDDDDD',
                    ],
//                    'endColor' => [
//                        'rgb' => 'DDDDDD',
//                    ],
                ];
                //FIXME: нужно сделать заливку сереньким для зебры
                // break not needed
            case DailyCellPurpose::CHANGE_ODD:
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_RIGHT;
                break;

            case DailyCellPurpose::CHANGE_POSITIVE_EVEN:
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'DDDDDD',
                    ],
//                    'endColor' => [
//                        'rgb' => 'DDDDDD',
//                    ],
                ];
                //FIXME: нужно сделать заливку сереньким для зебры
                // break not needed
            case DailyCellPurpose::CHANGE_POSITIVE_ODD:
                $styles['font']['color'] = ['rgb' => '008000'];
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_RIGHT;
                break;

            case DailyCellPurpose::CHANGE_NEGATIVE_EVEN:
                $styles['fill'] = [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'DDDDDD',
                    ],
//                    'endColor' => [
//                        'rgb' => 'DDDDDD',
//                    ],
                ];
                //FIXME: нужно сделать заливку сереньким для зебры
                // break not needed

            case DailyCellPurpose::CHANGE_NEGATIVE_ODD:
                $styles['font']['color'] = ['rgb' => '8C0004'];
                $styles['alignment']['horizontal'] = Style\Alignment::HORIZONTAL_RIGHT;
                break;
        }

        SpreadsheetStylerUtil::applyNoteStyle($excelCell, ['bold' => true]);
        SpreadsheetStylerUtil::applyStylesFromArray($excelCell, $styles);
    }
}
