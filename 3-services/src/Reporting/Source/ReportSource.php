<?php

namespace App\Reporting\Source;

use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;

/**
 * Саггрегированные данные, по которым удобно строить отчет
 */
abstract class ReportSource
{
    /**
     * @var Report
     */
    private $report;

    /**
     * @var ReportLine[]
     */
    private $lines;

    /**
     * @param ReportLine[] $lines
     */
    public function __construct(Report $report, array $lines)
    {
        $this->report = $report;
        $this->lines = $lines;
    }

    public function getReport(): Report
    {
        return $this->report;
    }

    /**
     * @return ReportLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }
}
