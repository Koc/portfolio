<?php

namespace App\Reporting\Report\Weekly;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\CssStyler;

class WeeklyCssStyler implements CssStyler
{
    public function getCss(): string
    {
        $tableCssClass = $this->getTableCssCLass();

        return <<<CSS
.$tableCssClass .table_caption {
    font-size: 14px;
    font-weight: bold;
    background: #FFCC99;
}

.$tableCssClass .column_caption {
    background: #C0C0C0;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
}

.$tableCssClass .row_caption {
    font-weight: bold;
}

.$tableCssClass .symbol_up {
    color: #008000;
}

.$tableCssClass .symbol_down {
    color: #7F0028;
}

CSS;
    }

    public function getCssClassesForCell(Cell $cell): array
    {
        $classes = [$cell->getPurpose()];

        if ($cell->getPurpose() === Cell::PURPOSE_CELL_CHANGE) {
            if ($cell->getValue() > 0) {
                $classes[] = 'change_up';
            } elseif ($cell->getValue() < 0) {
                $classes[] = 'change_down';
            }
        }

        return $classes;
    }

    public function getTableCssCLass(): string
    {
        return 'weekly';
    }
}
