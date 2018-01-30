<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bid")
 * @ORM\Entity
 */
class Bid
{
    const TARGET_OFFER = 'offer';
    const TARGET_DEMAND = 'demand';

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @var User
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $priceMin;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $priceMax;

    /**
     * @var string
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unit")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id", nullable=false)
     *
     * @var Unit
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BidCommodity")
     * @ORM\JoinColumn(name="commodity_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     *
     * @var BidCommodity
     */
    private $commodity;

    /**
     * @var string
     * @ORM\Column(type="string", length=1000, options={"default": "demand"})
     * @Assert\NotBlank()
     */
    private $direction;

    /**
     * @var string
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $conditions;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Bid constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPriceMin()
    {
        return $this->priceMin;
    }

    /**
     * @param string $priceMin
     */
    public function setPriceMin($priceMin)
    {
        $this->priceMin = $priceMin;
    }

    /**
     * @return string
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }

    /**
     * @param string $priceMax
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = (string)$currency;
    }

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     */
    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit;
    }

    /**
     * @return BidCommodity
     */
    public function getCommodity()
    {
        return $this->commodity;
    }

    /**
     * @param BidCommodity $commodity
     */
    public function setCommodity($commodity)
    {
        $this->commodity = $commodity;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param string $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
