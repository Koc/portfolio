<?php

namespace App\Reporting\Report\Weekly;

use App\Reporting\Generated\CellPurpose;

interface WeeklyCellPurpose extends CellPurpose
{
    public const COLUMN_SUB_CAPTION = 'column_sub_caption';

    public const SIMPLE_ODD = 'simple_odd';
    public const SIMPLE_EVEN = 'simple_even';

    public const CHANGE_POSITIVE_ODD = 'change_positive_odd';
    public const CHANGE_NEGATIVE_ODD = 'change_negative_odd';
    public const CHANGE_ODD = 'change_odd';
}
