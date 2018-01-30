<?php

namespace App\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Api\CompositeModel;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     shortName="Item",
 *     attributes={
 *         "normalization_context"={"groups"={"price_items"}},
 *         "denormalization_context"={"groups"={"price_items"}, "skip_non_existent_references"=true}
 *     },
 *     itemOperations={},
 *     collectionOperations={
 *         "save_batch"={
 *             "method"="POST",
 *             "route_name"="api_price_save_batch",
 *             "normalization_context"={"groups"={"price_items", "price_commodity"}},
 *             "swagger_context" = {
 *                 "summary" = "Update items by batch request.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "updatePriceItemsRequest",
 *                         "in" = "body",
 *                         "required" = true,
 *                         "schema"= {
 *                             "type" = "object",
 *                             "$schema": "http://json-schema.org/draft-03/schema",
 *                             "properties" = {
 *                                 "priceItemRequests" = {
 *                                     "type" = "array",
 *                                     "items"={
 *                                         "type" = "object",
 *                                         "properties" = {
 *                                             "item"={"type"="string"},
 *                                             "minPrice"={"type"="number"},
 *                                             "maxPrice"={"type"="number"},
 *                                             "avgPrice"={"type"="number"},
 *                                         }
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 },
 *                 "responses" = {
 *                     201 = {
 *                         "schema" = {
 *                             "type" = "array",
 *                             "items" = {"type" = "object", "$ref" = "#/definitions/Item"}
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 */
class UpdatePriceItemsRequest implements CompositeModel
{
    /**
     * @var UpdatePriceItemRequest[]
     *
     * @Groups({"price_items"})
     * @Assert\Valid()
     * @Assert\Count(min=1, max=1000)
     */
    private $priceItemRequests = [];

    /**
     * @return UpdatePriceItemRequest[]
     */
    public function getPriceItemRequests(): array
    {
        return $this->priceItemRequests;
    }

    public function addPriceItemRequest(UpdatePriceItemRequest $priceItemRequest): void
    {
        $this->priceItemRequests[] = $priceItemRequest;
    }

    public function removePriceItemRequest(UpdatePriceItemRequest $priceItemRequest): void
    {
        if (false !== $key = array_search($priceItemRequest, $this->priceItemRequests, true)) {
            array_splice($this->priceItemRequests, $key, 1);
        }
    }

    public function getPersistentObjects(): iterable
    {
        $items = [];
        foreach ($this->getPriceItemRequests() as $priceItemRequest) {
            $items[] = $priceItemRequest->updateItem();
        }

        return $items;
    }
}
