<?php

namespace App\Controller\Group;

use App\Entity\Price\Group;
use App\Reporting\GroupReportBuilder;
use App\Reporting\Report\Plants\PlantsGenerationRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class GenerateReportAction
{
    private $groupReportBuilder;

    private $urlGenerator;

    public function __construct(GroupReportBuilder $groupReportBuilder, UrlGeneratorInterface $urlGenerator)
    {
        $this->groupReportBuilder = $groupReportBuilder;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(Group $data, string $date_from, string $date_to, string $format)
    {
//        $generationRequest = new BasicGenerationRequest();
//        $generationRequest->setDateFrom(new \DateTime($date_from));
//        $generationRequest->setDateTo(new \DateTime($date_to));

        $generationRequest = new PlantsGenerationRequest();
        $generationRequest->setDate(new \DateTime($date_from));

        $reportResponse = $this->groupReportBuilder->buildGroupReport($data, $generationRequest, $format);

//        return new Response('');

        $fileName = $reportResponse->getFilename();
        $reportUrl = $this->urlGenerator->generate('api_group_download_reports', ['filename' => $fileName]);

        return new class($fileName, $reportUrl)
        {
            /**
             * @Groups({"group"})
             */
            public $reportName;

            /**
             * @Groups({"group"})
             */
            public $reportUrl;

            public function __construct(string $reportName, string $reportUrl)
            {
                $this->reportName = $reportName;
                $this->reportUrl = $reportUrl;
            }
        };
    }
}
