<?php

namespace App\Reporting\Source;

use App\Entity\Price\Report;
use App\Reporting\Command\GenerationRequest;
use App\Reporting\Source\Exception\UnsupportedGenerationRequestException;

/**
 * Источник данных, для генерации отчета. На основании входных параметров выдает данные, по которым дальше будет
 * генерироваться отчет. Обычно тут происходит обращение к БД через репозитории
 */
interface ReportSourceProvider
{
    /**
     * @throws UnsupportedGenerationRequestException
     */
    public function getReportSource(Report $report, GenerationRequest $request): ReportSource;
}
