<?php

namespace App\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Price\Item;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={},
 *     attributes={
 *         "normalization_context"={"groups"={"price_items"}},
 *         "denormalization_context"={"groups"={"price_items"}}
 *     }
 * )
 */
class UpdatePriceItemRequest
{
    /**
     * @var Item
     *
     * @Groups({"price_items"})
     *
     * @Assert\NotNull()
     * @Assert\Type(Item::class)
     */
    public $item;

    /**
     * @Groups({"price_items"})
     *
     * @Assert\Type("numeric")
     * @Assert\GreaterThanOrEqual(0)
     */
    public $minPrice;

    /**
     * @Groups({"price_items"})
     *
     * @Assert\Type("numeric")
     * @Assert\GreaterThanOrEqual(0)
     */
    public $maxPrice;

    /**
     * @Groups({"price_items"})
     *
     * @Assert\Type("numeric")
     * @Assert\GreaterThanOrEqual(0)
     */
    public $avgPrice;

    /**
     * @return Item Updated item
     */
    public function updateItem(): Item
    {
        if (!$this->item instanceof Item) {
            throw new \LogicException(sprintf('Expected "%s" instance for "item" property.', Item::class));
        }

        $this->item->setMinPrice($this->minPrice);
        $this->item->setMaxPrice($this->maxPrice);
        $this->item->setAvgPrice($this->avgPrice);

        return $this->item;
    }
}
