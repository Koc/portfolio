<?php

namespace App\Reporting;

use App\Reporting\Generated\GeneratedReport;

interface Renderer
{
    public const FORMAT_XLSX = 'xlsx';

    public const FORMAT_HTML = 'html';

    /**
     * @param GeneratedReport[] $reports
     * @param string[] $stylerClasses
     * @return ReportResponse
     */
    public function renderGeneratedReportCollection(array $reports, array $stylerClasses): ReportResponse;

    public static function getFormat(): string;
}
