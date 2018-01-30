<?php

namespace App\Entity\Price;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="ukr_tovgroup", schema="price")
 *
 * @ORM\Entity
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"group"}}
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET"
 *         },
 *         "generate_report"={
 *             "method"="GET",
 *             "route_name"="api_group_generate_report",
 *             "swagger_context" = {
 *                 "summary" = "Generates report for group per given dates in specified format.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "id",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string"
 *                     },
 *                     {
 *                         "name" = "date_from",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string",
 *                         "format" = "date"
 *                     },
 *                     {
 *                         "name" = "date_to",
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
 *         "download_reports"={
 *             "method"="GET",
 *             "route_name"="api_group_download_reports",
 *             "swagger_context" = {
 *                 "summary" = "Download reports for group per given file path.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "filename",
 *                         "in" = "path",
 *                         "required" = true,
 *                         "type" = "string"
 *                     }
 *                 },
 *                 "responses" = {
 *                     200 = {
 *                         "schema" = {"type" = "file"}
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id"="exact", "reports"="exact", "name"="partial"})
 */
class Group
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cod_gr", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"group", "report"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, name="name")
     *
     * @Groups({"group"})
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="sort_order")
     *
     * @Groups({"group"})
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(type="text", name="jeditor", nullable=true)
     * @Groups({"group"})
     */
    private $editorClass;

    /**
     * @var string
     * @ORM\Column(type="text", name="jgenerator", nullable=true)
     * @Groups({"group"})
     */
    private $generatorClass;

    /**
     * @var Collection|Report
     * @ORM\OneToMany(targetEntity="Report", mappedBy="group")
     * @ORM\OrderBy({"position" = "ASC"})
     * @Groups({"group"})
     */
    private $reports;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
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
    public function getEditorClass(): ? string
    {
        return $this->editorClass;
    }

    /**
     * @param string $editorClass
     */
    public function setEditorClass(string $editorClass): void
    {
        $this->editorClass = $editorClass;
    }

    /**
     * @return string
     */
    public function getGeneratorClass(): ? string
    {
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
     * @param Report $report
     */
    public function addReport(Report $report)
    {
        $this->reports[] = $report;
    }

    /**
     * @param Report $report
     */
    public function removeReport(Report $report)
    {
        $this->reports->removeElement($report);
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }
}
