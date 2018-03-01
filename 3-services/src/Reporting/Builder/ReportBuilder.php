<?php

namespace App\Reporting\Builder;

use App\Reporting\Command\GenerateReportCommand;
use App\Reporting\GenerationRequestFactory;
use App\Reporting\RendererFactory;
use App\Reporting\ReportingFactory;

class ReportBuilder
{
    private $generationRequestFactory;

    private $reportingFactory;

    private $rendererFactory;

    public function __construct(
        GenerationRequestFactory $generationRequestFactory,
        ReportingFactory $reportingFactory,
        RendererFactory $rendererFactory
    ) {
        $this->generationRequestFactory = $generationRequestFactory;
        $this->reportingFactory = $reportingFactory;
        $this->rendererFactory = $rendererFactory;
    }

    public function buildReport(GenerateReportCommand $command): ReportGenerationResult
    {
        $generatedReports = [];
        $stylers = [];

        $report = $command->getReport();
        $generatorClass = $report->getGeneratorClass();
        $generator = $this->reportingFactory->getGenerator($generatorClass);

        $generationRequest = $this->generationRequestFactory
            ->getGenerationRequest(
                $generator->getGenerationRequestClass(),
                $command->getGenerationRequest($generatorClass)
            );

        $sourceProvider = $this->reportingFactory->getSourceProvider($generator->getSourceProviderClass());

        $reportSource = $sourceProvider->getReportSource($report, $generationRequest);

        $generatedReport = $generator->generate($reportSource);
        $generatedReports[] = $generatedReport;

        $stylerClasses = $generator->getStylerClasses();
        $format = $command->getFormat();
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
