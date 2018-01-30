<?php

namespace AppBundle\Model\ResponseDTO;

use AppBundle\Model\BidDTO;
use JMS\Serializer\Annotation as Serializer;

class BidItemResponseDTO
{
    /**
     * Status text
     *
     * @Serializer\Type("string")
     * @var string
     */
    public $status;

    /**
     * Bid
     *
     * @Serializer\Type("AppBundle\Model\BidDTO")
     * @var BidDTO
     */
    public $item;

    /**
     * BidItemResponseDTO constructor.
     * @param string $status
     * @param BidDTO $item
     */
    public function __construct(BidDTO $item = null, string $status = 'success')
    {
        $this->item = $item;
        $this->status = $status;
    }
}
