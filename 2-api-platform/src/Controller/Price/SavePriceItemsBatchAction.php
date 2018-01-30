<?php

namespace App\Controller\Price;

use App\Entity\Price\Item;
use App\Model\UpdatePriceItemsRequest;
use Symfony\Component\Routing\Annotation\Route;

class SavePriceItemsBatchAction
{
    /**
     * @Route(
     *     name="api_price_save_batch",
     *     path="/api/items/batch",
     *     defaults={
     *         "_api_resource_class"=UpdatePriceItemsRequest::class,
     *         "_api_collection_operation_name"="save_batch"
     *     }
     * )
     * @return Item[]
     */
    public function __invoke(UpdatePriceItemsRequest $data): array
    {
        return $data->getPersistentObjects();
    }
}
