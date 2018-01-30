<?php

namespace App\Entity\WorldPrice;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Table(name="world.price")
 * @ApiResource(
 *     shortName="WorldItem",
 *     attributes={
 *         "force_eager"=false,
 *         "normalization_context"={"groups"={"world_price_commodity"}}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "id"="exact",
 *     "commodity"="exact",
 *     "price"="exact"
 * })
 *
 * @ORM\Entity(repositoryClass="App\Repository\WorldPrice\ItemRepository")
 */
class Item
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"world_price_commodity"})
     */
    private $id;

    /**
     * @var Commodity
     * @ORM\ManyToOne(targetEntity="Commodity", inversedBy="items")
     * @ORM\JoinColumn(name="cod_tov", referencedColumnName="cod_tov", nullable=false)
     * @Groups({"world_price_commodity"})
     */
    private $commodity;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="data")
     * @Groups({"world_price_commodity"})
     */
    private $publishedAt;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=2, name="mes")
     * @Groups({"world_price_commodity"})
     */
    private $contractMonth;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=4, name="god")
     * @Groups({"world_price_commodity"})
     */
    private $contractYear;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=10, scale=4, name="cena")
     * @Groups({"world_price_commodity"})
     */
    private $price;

    /**
     * @var bool
     * @ORM\Column(type="smallint", name="pr_ok")
     * @Groups({"world_price_commodity"})
     */
    private $isChecked;

    /**
     * Item constructor.
     */
    public function __construct()
    {
        $this->isChecked = false;
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

    /**
     * @param \DateTime $publishedAt
     */
    public function setPublishedAt(\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return string
     */
    public function getContractMonth(): ? string
    {
        return $this->contractMonth;
    }

    /**
     * @param string $contractMonth
     */
    public function setContractMonth(string $contractMonth): void
    {
        $this->contractMonth = $contractMonth;
    }

    /**
     * @return string
     */
    public function getContractYear(): ? string
    {
        return $this->contractYear;
    }

    /**
     * @param string $contractYear
     */
    public function setContractYear(string $contractYear): void
    {
        $this->contractYear = $contractYear;
    }

    /**
     * @return string
     */
    public function getPrice(): ? string
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isChecked(): ? bool
    {
        return $this->isChecked;
    }

    /**
     * @param bool $isChecked
     */
    public function setIsChecked(bool $isChecked): void
    {
        $this->isChecked = $isChecked;
    }

    public static function convertDateTimeToDateKey(\DateTime $dateTime): string
    {
        return $dateTime->format('Y-m-d');
    }

    public function getDateKey(): string
    {
        return self::convertDateTimeToDateKey($this->getPublishedAt());
    }
}
