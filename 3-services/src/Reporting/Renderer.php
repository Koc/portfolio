<?php

namespace App\Reporting;

use App\Reporting\Builder\ReportGenerationResult;
use App\Reporting\Generated\GeneratedReport;

interface Renderer
{
    public const FORMAT_XLSX = 'xlsx';

    public const FORMAT_HTML = 'html';

    /**
     * @param GeneratedReport[] $reports
     * @param string[] $stylerClasses
     * @return ReportGenerationResult
     */
    public function renderGeneratedReportCollection(array $reports, array $stylerClasses): ReportGenerationResult;

    public static function getFormat(): string;
}
