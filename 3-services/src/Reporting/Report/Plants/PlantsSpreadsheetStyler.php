<?php

namespace App\Reporting\Report\Plants;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\SpreadsheetStyler;
use App\Reporting\Renderer\SpreadsheetStylerUtil;
use PhpOffice\PhpSpreadsheet\Cell\Cell as ExcelCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style;

class PlantsSpreadsheetStyler implements SpreadsheetStyler
{
    public function applySheetStyling(Worksheet $worksheet): void
    {
        //FIXME: UBUNTU XLSX ширина столбцов в cм показывается, но измеряется в своих единицах
        // у меня 1 здесь = 0,24 см в excel
//        $worksheet->getColumnDimension('A')->setWidth("7,38");
//        $worksheet->getColumnDimension('B')->setWidth("1,14");
//        $worksheet->getColumnDimension('C')->setWidth("1,14");
//        $worksheet->getColumnDimension('D')->setWidth("1,14");
//        $worksheet->getColumnDimension('E')->setWidth("1,14");
//        $worksheet->getColumnDimension('F')->setWidth("1,14");
//        $worksheet->getColumnDimension('G')->setWidth("1,01");
//        $worksheet->getColumnDimension('H')->setWidth("1,01");
//        $worksheet->getColumnDimension('I')->setWidth("1,01");
//        $worksheet->getColumnDimension('J')->setWidth("0,4");
//        $worksheet->getColumnDimension('K')->setWidth("1,06");
//        $worksheet->getColumnDimension('L')->setWidth("0,4");
//        $worksheet->getColumnDimension('M')->setWidth("1,06");
//        $worksheet->getColumnDimension('N')->setWidth("0,4");
//        $worksheet->getColumnDimension('O')->setWidth("1,06");

        $worksheet->getColumnDimension('A')->setWidth("30.75");
        $worksheet->getColumnDimension('B')->setWidth("4.75");
        $worksheet->getColumnDimension('C')->setWidth("4.75");
        $worksheet->getColumnDimension('D')->setWidth("4.75");
        $worksheet->getColumnDimension('E')->setWidth("4.75");
        $worksheet->getColumnDimension('F')->setWidth("4.75");
        $worksheet->getColumnDimension('G')->setWidth("4.21");
        $worksheet->getColumnDimension('H')->setWidth("4.21");
        $worksheet->getColumnDimension('I')->setWidth("4.21");
        $worksheet->getColumnDimension('J')->setWidth("1.48");
        $worksheet->getColumnDimension('K')->setWidth("4.41");
        $worksheet->getColumnDimension('L')->setWidth("1.48");
        $worksheet->getColumnDimension('M')->setWidth("4.41");
        $worksheet->getColumnDimension('N')->setWidth("1.48");
        $worksheet->getColumnDimension('O')->setWidth("4.41");
    }

    public function applyCellStyling(ExcelCell $excelCell, Cell $cell): void
    {
        //TODO: extract common styles like in BasicSpreadsheetStyler
        $styles = [];
        switch ($cell->getPurpose()) {
            case PlantsCellPurpose::COLUMN_CAPTION:
                $styles = [
                    'font' => [
                        'bold' => true,
                        'name' => 'Arial',
                        'size' => '7',
                    ],
                    'alignment' => [
                        'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'CCFFCC',
                        ],
                        'endColor' => [
                            'rgb' => 'CCFFCC',
                        ],
                    ],
                ];

                break;
            case PlantsCellPurpose::ROW_CAPTION:
                $styles = [
                    'font' => [
                        'name' => 'Arial',
                        'size' => '7',
                    ],
                    'alignment' => [
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                ];
                break;

            case PlantsCellPurpose::SIMPLE:
                $styles = [
                    'font' => [
                        'bold' => true,
                        'name' => 'Arial',
                        'size' => '7',
                    ],
                    'alignment' => [
                        'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                ];
                break;

            case PlantsCellPurpose::CHANGE:
                $styles = [
                    'font' => [
                        'name' => 'Arial',
                        'size' => '7',
                    ],
                    'alignment' => [
                        'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                        'bottom' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                        'right' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'CBFEFE',
                        ],
                        'endColor' => [
                            'rgb' => 'CBFEFE',
                        ],
                    ],
                ];
                break;

            case PlantsCellPurpose::SYMBOL_UP:
                $styles = [
                    'font' => [
                        'name' => 'Arial',
                        'size' => '7',
                        'color' => [
                            'rgb' => '990000',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                        'bottom' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'CBFEFE',
                        ],
                        'endColor' => [
                            'rgb' => 'CBFEFE',
                        ],
                    ],
                ];
                break;

            case PlantsCellPurpose::SYMBOL_DOWN:
                $styles = [
                    'font' => [
                        'name' => 'Arial',
                        'size' => '7',
                    ],
                    'alignment' => [
                        'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                        'bottom' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'CBFEFE',
                        ],
                        'endColor' => [
                            'rgb' => 'CBFEFE',
                        ],
                    ],
                ];
                break;

            case PlantsCellPurpose::TABLE_CAPTION:
                $styles = [
                    'font' => [
                        'bold' => true,
                        'name' => 'Arial',
                        'size' => '8',
                        'color' => [
                            'rgb' => 'FCFCFB',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Style\Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '339966',
                            ],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '339966',
                        ],
                        'endColor' => [
                            'rgb' => '339966',
                        ],
                    ],
                ];
        }

        SpreadsheetStylerUtil::applyStylesFromArray($excelCell, $styles);
    }
}
