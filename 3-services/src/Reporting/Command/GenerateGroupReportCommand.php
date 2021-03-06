<?php

namespace App\Reporting\Command;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Group\GenerateReportsAction;
use App\Entity\Price\Group;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"reporting"}},
 *         "denormalization_context"={"groups"={"reporting"}},
 *     },
 *     shortName="GroupReporting",
 *     itemOperations={},
 *     collectionOperations={
 *         "generate"={
 *             "method"="POST",
 *             "path"="/groups/reporting/generate",
 *             "controller"=GenerateReportsAction::class,
 *             "swagger_context" = {
 *                 "summary" = "Generate reports for group.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "generateGroupReportCommand",
 *                         "in" = "body",
 *                         "required" = true,
 *                         "schema"= {
 *                             "type" = "object",
 *                             "$schema": "http://json-schema.org/draft-03/schema",
 *                             "properties" = {
 *                                 "group" = {
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
class GenerateGroupReportCommand extends AbstractGenerationCommand
{
    /**
     * @Groups({"reporting"})
     *
     * @var Group
     */
    private $group;

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }
}
