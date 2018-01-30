<?php

namespace App\Reporting\Renderer;

use App\Reporting\Generated\Cell;

interface CssStyler
{
    public function getCss();

    public function getCssClassesForCell(Cell $cell): array;

    public function getTableCssCLass(): string;
}
