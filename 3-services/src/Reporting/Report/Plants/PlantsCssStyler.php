<?php

namespace App\Reporting\Report\Plants;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\CssStyler;

class PlantsCssStyler implements CssStyler
{
    public function getCss(): string
    {
        $tableCssClass = $this->getTableCssCLass();

        return <<<CSS
.$tableCssClass .table_caption {
    font-size: 12px;
    font-weight: bold;
    background: #339966;
    color: #FCFCFB;
}

.$tableCssClass .column_caption {
    background: #CCFFCC;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
}

.$tableCssClass .row_caption {
    text-align: left;
}
.$tableCssClass .change {
    background: #CBFEFE;
}
CSS;
    }

    public function getCssClassesForCell(Cell $cell): array
    {
        return [$cell->getPurpose()];
    }

    public function getTableCssCLass(): string
    {
        return 'plants';
    }
}
