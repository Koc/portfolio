<?php

namespace App\Controller\Report;

use App\Model\ReportGenerationResponse;
use App\Reporting\Builder\ReportBuilder;
use App\Reporting\Command\GenerateReportCommand;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateAction
{
    private $reportBuilder;

    private $urlGenerator;

    public function __construct(ReportBuilder $reportBuilder, UrlGeneratorInterface $urlGenerator)
    {
        $this->reportBuilder = $reportBuilder;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(GenerateReportCommand $data)
    {
        $reportResponse = $this->reportBuilder->buildReport($data);

        $fileName = $reportResponse->getFilename();
        $reportUrl = $this->urlGenerator->generate('api_reporting_download_result', ['filename' => $fileName]);

        return new ReportGenerationResponse($fileName, $reportUrl);
    }
}
