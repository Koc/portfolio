<?php

namespace AppBundle\Model;

use AppBundle\Entity\Bid;
use Symfony\Component\Validator\Constraints as Assert;

class BidsFilterDTO extends AbstractFilterDTO
{
    const ORDER_BY_COMMODITY_NAME = 'commodity_name';
    const ORDER_BY_COMPANY_NAME = 'company_name';
    const ORDER_BY_DATE = 'date';

    /**
     * @var string
     */
    public $target;

    /**
     * @var string
     */
    public $order;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $commodities;

    /**
     * @var \DateTime
     *
     * @Assert\Date()
     */
    public $dateFrom;

    /**
     * @var \DateTime
     *
     * @Assert\Date()
     */
    public $dateTo;

    /**
     * @var Bid[]
     */
    public $bids;
}
