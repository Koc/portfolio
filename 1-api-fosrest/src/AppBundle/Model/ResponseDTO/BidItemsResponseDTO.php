<?php

namespace AppBundle\Model\ResponseDTO;

use JMS\Serializer\Annotation as Serializer;

class BidItemsResponseDTO extends AbstractItemsResponseDTO
{
    /**
     * Items collection
     *
     * @Serializer\Type("array<AppBundle\Model\BidDTO>")
     * @var array
     */
    public $items;
}
