<?php

namespace App\Reporting\Renderer;

use App\Reporting\Generated\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Cell as ExcelCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface SpreadsheetStyler
{
    public function applySheetStyling(Worksheet $worksheet): void;

    public function applyCellStyling(ExcelCell $excelCell, Cell $cell): void;
}
