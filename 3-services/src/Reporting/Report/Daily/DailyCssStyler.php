<?php

namespace App\Reporting\Report\Daily;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer\CssStyler;

class DailyCssStyler implements CssStyler
{
    public function getCss(): string
    {
        $tableCssClass = $this->getTableCssCLass();

        //FIXME: цвет от фонаря, пробей нужный для зебры

        return <<<CSS
.$tableCssClass .table_caption {
    font-size: 14px;
    font-weight: bold;
    background: #00CCFF;
}

.$tableCssClass .column_caption {
    background: #369684;
    color: #FFFFF0;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
}

.$tableCssClass .column_sub_caption {
    font-weight: bold;
    background: #B3E9C2;
}

.$tableCssClass .simple_even, .$tableCssClass .change_positive_even, .$tableCssClass .change_negative_even,.$tableCssClass .change_even {
    background: #bbb;
}

.$tableCssClass .change_positive_odd, .$tableCssClass .change_positive_even {
    color: #008000;
}

.$tableCssClass .change_negative_odd, .$tableCssClass .change_negaive_even {
    color: #8C0004;
}
CSS;
    }

    public function getCssClassesForCell(Cell $cell): array
    {
        $classes = [$cell->getPurpose()];

        return $classes;
    }

    public function getTableCssCLass(): string
    {
        return 'daily';
    }
}
