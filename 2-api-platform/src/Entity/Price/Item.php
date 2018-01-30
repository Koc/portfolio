<?php

namespace App\Entity\Price;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Price\ItemRepository")
 * @ORM\Table(name="ukr_pricer", schema="price")
 * @ApiResource(attributes={
 *     "force_eager"=false,
 *     "normalization_context"={"groups"={"price_commodity"}}
 *     })
 */
class Item
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"price_commodity"})
     */
    private $id;

    /**
     * @var Commodity
     * @ORM\ManyToOne(targetEntity="Commodity", inversedBy="items")
     * @ORM\JoinColumn(name="cod_tov", referencedColumnName="cod_tov", nullable=false)
     */
    private $commodity;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="data")
     * @Groups({"price_commodity"})
     */
    private $publishedAt;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="cminr")
     * @Groups({"price_commodity"})
     */
    private $minPrice;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="cmaxr")
     * @Groups({"price_commodity"})
     */
    private $maxPrice;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="cavg")
     * @Groups({"price_commodity"})
     */
    private $avgPrice;

    /**
     * @var integer
     * @ORM\Column(type="smallint", name="pr_ok")
     * @Groups({"price_commodity"})
     */
    private $isChecked;

    /**
     * @var integer
     * @ORM\Column(type="smallint", name="pr_per")
     * @Groups({"price_commodity"})
     */
    private $periodicity;

    /**
     * Item constructor.
     */
    public function __construct()
    {
        $this->isChecked = 0;
    }

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
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
     * @return \DateTime
     */
    public function getPublishedAt(): ? \DateTime
    {
        return $this->publishedAt;
    }

    public static function convertDateTimeToDateKey(\DateTime $dateTime): string
    {
        return $dateTime->format('Y-m-d');
    }

    public function getDateKey(): string
    {
        return self::convertDateTimeToDateKey($this->getPublishedAt());
    }

    /**
     * @param \DateTime $publishedAt
     */
    public function setPublishedAt(\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return int
     */
    public function getMinPrice(): ? int
    {
        return $this->minPrice;
    }

    /**
     * @param int $minPrice
     */
    public function setMinPrice(int $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return int
     */
    public function getMaxPrice(): ? int
    {
        return $this->maxPrice;
    }

    /**
     * @param int $maxPrice
     */
    public function setMaxPrice(int $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * @return int
     */
    public function getAvgPrice(): ? int
    {
        return $this->avgPrice;
    }

    /**
     * @param int $avgPrice
     */
    public function setAvgPrice(int $avgPrice): void
    {
        $this->avgPrice = $avgPrice;
    }

    /**
     * @return int
     */
    public function isChecked(): ? int
    {
        return $this->isChecked;
    }

    /**
     * @return bool
     */
    public function getIsChecked(): ? bool
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
}
