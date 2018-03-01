<?php

namespace App\Controller\Group;

use App\Model\ReportGenerationResponse;
use App\Reporting\Builder\GroupReportBuilder;
use App\Reporting\Command\GenerateGroupReportCommand;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateReportsAction
{
    private $reportBuilder;

    private $urlGenerator;

    public function __construct(GroupReportBuilder $reportBuilder, UrlGeneratorInterface $urlGenerator)
    {
        $this->reportBuilder = $reportBuilder;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(GenerateGroupReportCommand $data)
    {
        $reportResponse = $this->reportBuilder->buildGroupReport($data);

        $fileName = $reportResponse->getFilename();
        $reportUrl = $this->urlGenerator->generate('api_reporting_download_result', ['filename' => $fileName]);

        return new ReportGenerationResponse($fileName, $reportUrl);
    }
}
