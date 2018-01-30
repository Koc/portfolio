<?php

namespace App\Reporting\Report\Plants;

use App\Reporting\Generated\CellPurpose;

interface PlantsCellPurpose extends CellPurpose
{
    public const SYMBOL_UP = 'symbol_up';
    public const SYMBOL_DOWN = 'symbol_down';
    public const CHANGE = 'change';
}
