<?php

namespace AppBundle\Model;

use AppBundle\Entity\Region;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractFilterDTO
{
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value="0")
     * @Assert\LessThanOrEqual(value="200")
     * @Assert\Type(type="numeric")
     */
    public $limit = 20;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\Type(type="numeric")
     */
    public $offset = 0;

    /**
     * @var Region
     */
    public $region;

    /**
     * @var string
     * @Assert\Length(max="3")
     */
    public $country;

    public function getPage()
    {
        return $this->offset > 1 ? round($this->offset / $this->limit) : 1;
    }
}
