<?php

namespace App\Entity\Price;

use App\Reporting\Report\Basic\BasicGenerator;
use App\Reporting\Report\Daily\DailyGenerator;
use App\Reporting\Report\Plants\PlantsGenerator;
use App\Reporting\Report\Weekly\WeeklyGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="greport", schema="price")
 * @ORM\Entity(repositoryClass="App\Repository\Price\ReportRepository")
 *
 * @ApiResource(
 *     attributes={
 *     "normalization_context"={"groups"={"report"}}
 *     },
 *        itemOperations={
 *         "get"={
 *             "method"="GET"
 *         },
 *         "generate_weekly_report"={
 *             "method"="GET",
 *             "route_name"="api_generate_weekly_report",
 *             "swagger_context" = {
 *                 "summary" = "Generates report per given date in specified format.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "id",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string"
 *                     },
 *                     {
 *                         "name" = "date",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string",
 *                         "format" = "date"
 *                     },
 *                     {
 *                         "name" = "format",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string",
 *                         "enum" = {"html", "xlsx"}
 *                     }
 *                 },
 *                 "responses" = {
 *                     200 = {
 *                         "schema" = {"type" = "object", "properties" = {"reportName" = {"type" = "string"}, "reportUrl" = {"type" = "string"}}}
 *                     }
 *                 }
 *             }
 *         },
 *         "generate_daily_report"={
 *             "method"="GET",
 *             "route_name"="api_generate_daily_report",
 *             "swagger_context" = {
 *                 "summary" = "Generates report per given date in specified format.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "id",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string"
 *                     },
 *                     {
 *                         "name" = "date",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string",
 *                         "format" = "date"
 *                     },
 *                     {
 *                         "name" = "format",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string",
 *                         "enum" = {"html", "xlsx"}
 *                     }
 *                 },
 *                 "responses" = {
 *                     200 = {
 *                         "schema" = {"type" = "object", "properties" = {"reportName" = {"type" = "string"}, "reportUrl" = {"type" = "string"}}}
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id"="exact", "group"="exact", "name"="partial"})
 */
class Report
{
    /**
     * @var string
     * @ORM\Column(name="greport", type="guid")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"report", "group"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="reports")
     * @ORM\JoinColumn(name="cod_gr", referencedColumnName="cod_gr", nullable=false)
     *
     * @Groups({"report"})
     *
     * @var Group
     */
    private $group;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     *
     * @Groups({"report", "group"})
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="sort_order", options={"default": 0})
     * @Groups({"report", "group"})
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(type="text", name="jgenerator")
     *
     * @Groups({"report", "group"})
     */
    private $generatorClass;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="ReportLine", mappedBy="report")
     *
     * @Groups({"report"})
     */
    private $lines;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $this->position = 0;
        $this->lines = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): ? string
    {
        return $this->id;
    }

    /**
     * @return Group
     */
    public function getGroup(): ? Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getName(): ? string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPosition(): ? int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getGeneratorClass(): ? string
    {
        //FIXME: DailyGenerator
        if ($this->generatorClass == 'WeeklyGenerator') {
            return WeeklyGenerator::class;
        } elseif ($this->generatorClass == 'DailyGenerator') {
            return DailyGenerator::class;
        }

        return $this->generatorClass;
    }

    /**
     * @param string $generatorClass
     */
    public function setGeneratorClass(string $generatorClass): void
    {
        $this->generatorClass = $generatorClass;
    }

    /**
     * @param ReportLine $reportLine
     */
    public function addLine(ReportLine $reportLine)
    {
        $this->lines[] = $reportLine;
    }

    /**
     * @param ReportLine $reportLine
     */
    public function removeLine(ReportLine $reportLine)
    {
        $this->lines->removeElement($reportLine);
    }

    /**
     * @return Collection|Selectable|ReportLine[]
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }
}
