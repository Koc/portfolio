<?php

namespace AppBundle\Model\ResponseDTO;

use AppBundle\Model\AbstractFilterDTO;
use JMS\Serializer\Annotation as Serializer;
use Knp\Component\Pager\Pagination\PaginationInterface;

abstract class AbstractItemsResponseDTO
{
    public $items;

    /**
     * Total result count
     * @Serializer\Type(name="integer")
     * @var int
     */
    public $total;

    /**
     * Limit result count
     *
     * @Serializer\Type(name="integer")
     * @var int
     */
    public $limit;

    /**
     * Offset result
     *
     * @Serializer\Type(name="integer")
     * @var int
     */
    public $offset;

    /**
     * BidsResponseDTO constructor.
     * @param PaginationInterface $pagination
     * @param AbstractFilterDTO $filterDTO
     */
    public function __construct(PaginationInterface $pagination, AbstractFilterDTO $filterDTO)
    {
        $this->items = $pagination->getItems();
        $this->total = $pagination->getTotalItemCount();
        $this->limit = $filterDTO->limit;
        $this->offset = $filterDTO->offset;
    }
}
