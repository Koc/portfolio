<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Intl\Intl;

class BidDTO
{
    /**
     * Object identity
     *
     * @Serializer\Type(name="integer")
     * @var int
     */
    public $id;

    /**
     * Date created
     *
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     * @var \DateTime
     */
    public $createdAt;

    /**
     * Direction
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $direction;

    /**
     * Bid commodity Name
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $commodityName;

    /**
     * Price max
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $priceMax;

    /**
     * Country
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $country;

    /**
     * Name of region
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $regionName;

    /**
     * Name of company
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $company;

    /**
     * Conditions
     *
     * @Serializer\Type(name="string")
     * @var string
     */
    public $conditions;

    public function __construct(
        $id,
        \DateTime $createdAt,
        ?string $direction,
        ?string $commodityName,
        ?string $priceMax,
        ?string $country,
        ?string $regionName,
        ?string $company,
        ?string $conditions,
        string $locale
    ) {
        $this->id = (int)$id;
        $this->createdAt = $createdAt;
        $this->direction = (string)$direction;
        $this->commodityName = (string)$commodityName;
        $this->priceMax = (string)$priceMax;
        $this->country = (string)Intl::getRegionBundle()->getCountryName($country, $locale);
        $this->regionName = (string)$regionName;
        $this->company = (string)$company;
        $this->conditions = (string)$conditions;
    }
}
