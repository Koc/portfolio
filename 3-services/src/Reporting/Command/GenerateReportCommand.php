<?php

namespace App\Reporting\Command;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Report\GenerateAction;
use App\Entity\Price\Report;
use Symfony\Component\Serializer\Annotation\Groups;

// curl -X POST "http://apk-wm-api.test/api/reporting/generate" -H  "accept: application/ld+json" -H  "Content-Type: application/ld+json" -d "{  \"report\": \"/api/reports/05e74904-4a73-4044-90aa-fab8e83a32ca\",  \"generationRequests\": {\"PlantsGenerator\": {\"date\": \"2018-08-01\"}},  \"format\": \"html\"}"

//{
//    "report": "/api/reports/05e74904-4a73-4044-90aa-fab8e83a32ca",
//  "generationRequests": {"PlantsGenerator": {"date": "2018-08-01"}},
//  "format": "html"
//}

/**
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"reporting"}},
 *         "denormalization_context"={"groups"={"reporting"}},
 *     },
 *     shortName="Reporting",
 *     itemOperations={},
 *     collectionOperations={
 *         "generate"={
 *             "method"="POST",
 *             "path"="/reporting/generate",
 *             "controller"=GenerateAction::class,
 *             "swagger_context" = {
 *                 "summary" = "Generate report.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "generateReportCommand",
 *                         "in" = "body",
 *                         "required" = true,
 *                         "schema"= {
 *                             "type" = "object",
 *                             "$schema": "http://json-schema.org/draft-03/schema",
 *                             "properties" = {
 *                                 "report" = {
 *                                     "type" = "string"
 *                                 },
 *                                 "generationRequests" = {
 *                                     "type" = "object"
 *                                 },
 *                                 "format" = {
 *                                     "type" = "string"
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 },
 *                 "responses" = {
 *                     201 = {
 *                         "schema" = {
 *                             "type" = "object",
 *                             "properties" = {
 *                                 "reportFilename" = {"type" = "string"},
 *                                 "reportUrl" = {"type" = "string"}
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 */
class GenerateReportCommand extends AbstractGenerationCommand
{
    /**
     * @Groups({"reporting"})
     *
     * @var Report
     */
    private $report;

    public function getReport(): Report
    {
        return $this->report;
    }

    public function setReport(Report $report): void
    {
        $this->report = $report;
    }
}
