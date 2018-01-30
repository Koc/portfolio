<?php

namespace App\Entity\WorldPrice;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Table(name="tovar", schema="world")
 * @ApiResource(
 *     shortName="WorldCommodity",
 *     attributes={
 *         "force_eager"=false,
 *         "normalization_context"={"groups"={"world_price_commodity"}}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "id"="exact",
 *     "commodity"="exact",
 *     "name"="partial",
 *     "exchanges"="partial",
 *     "tradeSource"="partial",
 *     "countryName"="exact"
 * })
 * @ORM\Entity(repositoryClass="App\Repository\WorldPrice\CommodityRepository")
 */
class Commodity
{
    CONST GRAIN = 1;
    CONST OIL = 2;
    CONST SUGAR = 3;

    public const FUTURE = 0;
    public const CASH = 1;

    public const COMMODITIES_BY_GROUPS = [
        'ПШЕНИЦА' => [3, 49, 50, 51, 14, 87, 80, 88, 89, 81, 72, 76, 68, 33, 34, 17, 78, 1, 12, 29],
        'ГРУБЫЕ ЗЕРНОВЫЕ' => [2, 30, 79, 60, 44, 47, 45, 35, 46, 77, 86, 4, 18, 8],
        'МАСЛИЧНЫЕ' => [16, 64, 32, 52, 84, 5, 59, 13, 57, 36, 31, 71, 7, 55],
        'РАСТИТЕЛЬНЫЕ МАСЛА' => [69, 73, 75, 53, 83, 28, 85, 27, 22, 48, 21, 23, 26, 24, 25],
        'ШРОТЫ МАСЛИЧНЫХ' => [39, 74, 37, 65, 58, 66, 54, 38, 40, 42, 41],
        'САХАР' => [11, 15, 9, 6],
    ];

    /**
     * @var integer
     * @ORM\Column(name="cod_tov", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"world_price_commodity"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=30, name="naim")
     * @Groups({"world_price_commodity"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=3, name="val")
     * @Groups({"world_price_commodity"})
     */
    private $currency;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, name="edizm")
     * @Groups({"world_price_commodity"})
     */
    private $unit;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="pr_nal", options={"default": false})
     * @Groups({"world_price_commodity"})
     */
    private $isCash;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="pr_ed", options={"default": false})
     * @Groups({"world_price_commodity"})
     */
    private $isDaily;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="pr_en", options={"default": false})
     * @Groups({"world_price_commodity"})
     */
    private $isWeekly;


    /**
     * @var string
     * @ORM\Column(type="string", length=50, name="fname", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $filename;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, name="strana", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $countryName;

    /**
     * @var integer
     * @ORM\Column(type="smallint", name="df")
     * @Groups({"world_price_commodity"})
     */
    private $group;

    /**
     * @var string
     * @ORM\Column(type="string", length=20, name="birg", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $exchanges;

    /**
     * @var string
     * @ORM\Column(type="string", length=20, name="usl", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $deliveryDescription;

    /**
     * @var string
     * @ORM\Column(type="text", length=2000, name="pravilo", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $convertationRule;

    /**
     * @var string
     * @ORM\Column(type="text", length=2000, name="prim", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $note;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, name="ist", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $tradeSource;

    /**
     * @var string
     * @ORM\Column(type="text", length=500, name="source", nullable=true)
     * @Groups({"world_price_commodity"})
     */
    private $infoSource;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=4, name="n_ed", options={"default": 0})
     * @Groups({"world_price_commodity"})
     */
    private $dailyPosition;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=4, name="n_en", options={"default": 0})
     * @Groups({"world_price_commodity"})
     */
    private $weeklyPosition;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Item", mappedBy="commodity")
     */
    private $items;

    /**
     * Commodity constructor.
     */
    public function __construct()
    {
        $this->isCash = false;
        $this->isDaily = false;
        $this->isWeekly = false;
        $this->dailyPosition = 0;
        $this->weeklyPosition = 0;
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
    public function isCash(): ? bool
    {
        return $this->isCash;
    }

    /**
     * @param bool $isCash
     */
    public function setIsCash(bool $isCash): void
    {
        $this->isCash = $isCash;
    }

    /**
     * @return bool
     */
    public function isDaily(): ? bool
    {
        return $this->isDaily;
    }

    /**
     * @param bool $isDaily
     */
    public function setIsDaily(bool $isDaily): void
    {
        $this->isDaily = $isDaily;
    }

    /**
     * @return bool
     */
    public function isWeekly(): ? bool
    {
        return $this->isWeekly;
    }

    /**
     * @param bool $isWeekly
     */
    public function setIsWeekly(bool $isWeekly): void
    {
        $this->isWeekly = $isWeekly;
    }

    /**
     * @return string
     */
    public function getFilename(): ? string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getCountryName(): ? string
    {
        return $this->countryName;
    }

    /**
     * @param string $countryName
     */
    public function setCountryName(string $countryName): void
    {
        $this->countryName = $countryName;
    }

    /**
     * @return int
     */
    public function getGroup(): ? int
    {
        return $this->group;
    }

    /**
     * @param int $group
     */
    public function setGroup(int $group): void
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getExchanges(): ? string
    {
        return $this->exchanges;
    }

    /**
     * @param string $exchanges
     */
    public function setExchanges(string $exchanges): void
    {
        $this->exchanges = $exchanges;
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
    public function getConvertationRule(): ? string
    {
        return $this->convertationRule;
    }

    /**
     * @param string $convertationRule
     */
    public function setConvertationRule(string $convertationRule): void
    {
        $this->convertationRule = $convertationRule;
    }

    /**
     * @return string
     */
    public function getNote(): ? string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getTradeSource(): ? string
    {
        return $this->tradeSource;
    }

    /**
     * @param string $tradeSource
     */
    public function setTradeSource(string $tradeSource): void
    {
        $this->tradeSource = $tradeSource;
    }

    /**
     * @return string
     */
    public function getInfoSource(): ? string
    {
        return $this->infoSource;
    }

    /**
     * @param string $infoSource
     */
    public function setInfoSource(string $infoSource): void
    {
        $this->infoSource = $infoSource;
    }

    /**
     * @return string
     */
    public function getDailyPosition(): ? string
    {
        return $this->dailyPosition;
    }

    /**
     * @param string $dailyPosition
     */
    public function setDailyPosition(string $dailyPosition): void
    {
        $this->dailyPosition = $dailyPosition;
    }

    /**
     * @return string
     */
    public function getWeeklyPosition(): ? string
    {
        return $this->weeklyPosition;
    }

    /**
     * @param string $weeklyPosition
     */
    public function setWeeklyPosition(string $weeklyPosition): void
    {
        $this->weeklyPosition = $weeklyPosition;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): ? Collection
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
    }

    public static function getAllCommoditiesIds()
    {
        return array_merge(...array_values(self::COMMODITIES_BY_GROUPS));
    }
}
