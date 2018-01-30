<?php

namespace App\Entity\Price;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

use App\Filter\PricesGroupFilter;
use App\Filter\PricesDateFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="price.ukr_tovar")
 * @ApiResource(attributes={
 *     "force_eager"=false,
 *     "filters"={PricesGroupFilter::class, PricesDateFilter::class},
 *     "normalization_context"={"groups"={"price_commodity"}}
 *     })
 * @ORM\Entity
 */
class Commodity
{

    CONST DEMAND_TYPE = 0;
    CONST OFFER_TYPE = 1;

    CONST WEEKLY = 1;
    CONST DAILY = 2;
    CONST BOTH = 3;

    /**
     * @var integer
     * @ORM\Column(name="cod_tov", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="price.price_seq_ukr_tovar")
     *
     * @Groups({"price_commodity"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, name="naim_poln")
     *
     * @Groups({"price_commodity"})
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, name="naim_sokr")
     *
     * @Groups({"price_commodity"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, name="naim_eng")
     * @Groups({"price_commodity"})
     */
    private $nameEn;

    /**
     * @var integer
     * @ORM\Column(type="smallint", name="pr_sp_pr")
     * @Groups({"price_commodity"})
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string", length=3, name="cod_usl")
     *
     * @Groups({"price_commodity"})
     */
    private $deliveryCondition;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, name="text_usl")
     * @Groups({"price_commodity"})
     */
    private $deliveryDescription;

    /**
     * @var string
     * @ORM\Column(type="string", length=3, name="cod_val")
     * @Groups({"price_commodity"})
     */
    private $currency;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, name="ed_izm")
     * @Groups({"price_commodity"})
     */
    private $unit;

    /**
     * @var bool
     * @ORM\Column(type="smallint", name="pr_prov")
     * @Groups({"price_commodity"})
     */
    private $isChecked;

    /**
     * @var bool
     * @ORM\Column(type="smallint", name="pr_act", options={"default": 1})
     * @Groups({"price_commodity"})
     */
    private $isActive;

    /**
     * @var integer
     * @ORM\Column(type="smallint", name="pr_per", options={"default": 1})
     * @Groups({"price_commodity"})
     */
    private $periodicity;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="ReportLine", mappedBy="commodity")
     */
    private $reportLines;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Item", mappedBy="commodity")
     * @Groups({"price_commodity"})
     */
    private $items;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="GroupCommodity", mappedBy="commodity")
     * @Groups({"price_commodity"})
     */
    protected $groupCommodities;

    /**
     * Commodity constructor.
     */
    public function __construct()
    {
        $this->isActive = 1;
        $this->deliveryCondition = '';
        $this->periodicity = self::WEEKLY;
        $this->reportLines = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->groupCommodities = new ArrayCollection();
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
    public function getFullName(): ? string
    {
        return (string)$this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getName(): ? string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNameEn(): ? string
    {
        return (string)$this->nameEn;
    }

    /**
     * @param string $nameEn
     */
    public function setNameEn(string $nameEn): void
    {
        $this->nameEn = $nameEn;
    }

    /**
     * @return int
     */
    public function getType(): ? int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDeliveryCondition(): ? string
    {
        return $this->deliveryCondition;
    }

    /**
     * @param string $deliveryCondition
     */
    public function setDeliveryCondition(string $deliveryCondition): void
    {
        $this->deliveryCondition = $deliveryCondition;
    }

    /**
     * @return string
     */
    public function getDeliveryDescription(): ? string
    {
        return $this->deliveryDescription;
    }

    /**
     * @param string $deliveryDescription
     */
    public function setDeliveryDescription(string $deliveryDescription): void
    {
        $this->deliveryDescription = $deliveryDescription;
    }

    /**
     * @return string
     */
    public function getCurrency(): ? string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getUnit(): ? string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return bool
     */
    public function isChecked(): ? bool
    {
        return (bool)$this->isChecked;
    }

    /**
     * @param bool $isChecked
     */
    public function setIsChecked(bool $isChecked): void
    {
        $this->isChecked = (int)$isChecked;
    }

    /**
     * @return bool
     */
    public function isActive(): ? bool
    {
        return (bool)$this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = (int)$isActive;
    }

    /**
     * @return int
     */
    public function getPeriodicity(): ? int
    {
        return $this->periodicity;
    }

    /**
     * @param int $periodicity
     */
    public function setPeriodicity(int $periodicity): void
    {
        $this->periodicity = $periodicity;
    }

    /**
     * @param ReportLine $reportLine
     */
    public function addReportLine(ReportLine $reportLine)
    {
        $this->reportLines[] = $reportLine;
    }

    /**
     * @param ReportLine $reportLine
     */
    public function removeReportLine(ReportLine $reportLine)
    {
        $this->reportLines->removeElement($reportLine);
    }

    /**
     * @return Collection|ReportLine[]
     */
    public function getReportLines(): Collection
    {
        return $this->reportLines;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item) : void
    {
        $this->items[] = $item;
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item) : void
    {
        $this->items->removeElement($item);
    }

    /**
     * @return Collection|ReportLine[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @return Collection
     */
    public function getGroupCommodities(): Collection
    {
        return $this->groupCommodities;
    }
}
