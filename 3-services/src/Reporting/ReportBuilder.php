<?php

namespace App\Reporting;

use App\Entity\Price\Report;
use App\Reporting\Command\GenerationRequest;

class ReportBuilder
{
    private $reportingFactory;

    private $rendererFactory;

    public function __construct(ReportingFactory $reportingFactory, RendererFactory $rendererFactory)
    {
        $this->reportingFactory = $reportingFactory;
        $this->rendererFactory = $rendererFactory;
    }

    public function buildReport(Report $report, GenerationRequest $generationRequest, string $format): ReportResponse
    {
        $generatedReports = [];
        $stylers = [];

        $generator = $this->reportingFactory->getGenerator($report->getGeneratorClass());
        $sourceProvider = $this->reportingFactory->getSourceProvider($generator->getSourceProviderClass());

        $reportSource = $sourceProvider->getReportSource($report, $generationRequest);

        $generatedReport = $generator->generate($reportSource);
        $generatedReports[] = $generatedReport;

        $stylerClasses = $generator->getStylerClasses();
        if (!isset($stylerClasses[$format])) {
            throw new \InvalidArgumentException(
                sprintf('Missing styler for "%s" format of "%s" generator.', $format, get_class($generator))
            );
        }

        $stylers[] = $stylerClasses[$format];
        $renderer = $this->rendererFactory->getRenderer($format);

        return $renderer->renderGeneratedReportCollection($generatedReports, $stylers);
    }
}
