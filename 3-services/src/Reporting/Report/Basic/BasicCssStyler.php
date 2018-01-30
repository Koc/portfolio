<?php

namespace App\Reporting\Report\Basic;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\CssStyler;

class BasicCssStyler implements CssStyler
{
    public function getCss(): string
    {
        $tableCssClass = $this->getTableCssCLass();

        return <<<CSS
.$tableCssClass .table_caption {
    font-size: 12px;
    font-weight: bold;
    background: #888888;
}

.$tableCssClass .column_caption {
    background: #888888;
    font-weight: bold;
    border: 1px solid #000;
}

.$tableCssClass .row_caption {
    text-align: left;
}
CSS;
    }

    public function getCssClassesForCell(Cell $cell): array
    {
        return [$cell->getPurpose()];
    }

    public function getTableCssCLass(): string
    {
        return 'basic';
    }
}
