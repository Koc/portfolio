<?php

namespace App\Entity\Price;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="gline", schema="price")
 * @ApiResource(attributes={"force_eager"=false})
 * @ORM\Entity(repositoryClass="App\Repository\Price\ReportLineRepository")
 */
class ReportLine
{
    /**
     * @var string
     * @ORM\Column(name="gline", type="guid")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"report"})
     */
    private $id;

    /**
     * @var Report
     * @ORM\ManyToOne(targetEntity="Report", inversedBy="lines")
     * @ORM\JoinColumn(name="greport", referencedColumnName="greport", nullable=false)
     */
    private $report;

    /**
     * @var Commodity
     * @ORM\ManyToOne(targetEntity="Commodity", inversedBy="reportLines")
     * @ORM\JoinColumn(name="cod_tov", referencedColumnName="cod_tov", nullable=true)
     */
    private $commodity;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="sort_order", options={"default": 0})
     * @Groups({"report"})
     */
    private $position;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="in_graph")
     * @Groups({"report"})
     */
    private $isUsedInChart;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     * @Groups({"report"})
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", name="sname")
     * @Groups({"report"})
     */
    private $subtitle;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * @return string
     */
    public function getId(): ? string
    {
        return $this->id;
    }

    /**
     * @return Report
     */
    public function getReport(): ? Report
    {
        return $this->report;
    }

    /**
     * @param Report $report
     */
    public function setReport(Report $report): void
    {
        $this->report = $report;
    }

    /**
     * @return Commodity
     */
    public function getCommodity(): ? Commodity
    {
        return $this->commodity;
    }

    /**
     * @param Commodity $commodity
     */
    public function setCommodity(Commodity $commodity): void
    {
        $this->commodity = $commodity;
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
     * @return bool
     */
    public function isUsedInChart(): ? bool
    {
        return $this->isUsedInChart;
    }

    /**
     * @param bool $isUsedInChart
     */
    public function setIsUsedInChart(bool $isUsedInChart): void
    {
        $this->isUsedInChart = $isUsedInChart;
    }

    /**
     * @return string
     */
    public function getTitle(): ? string
    {
        return (string)$this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle(): ? string
    {
        return (string)$this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }
}
