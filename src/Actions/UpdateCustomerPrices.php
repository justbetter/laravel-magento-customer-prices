<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use JustBetter\MagentoClient\Client\Magento;
use JustBetter\MagentoCustomerPrices\Contracts\UpdatesMagentoCustomerPrices;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;

class UpdateCustomerPrices implements UpdatesMagentoCustomerPrices
{
    public function __construct(protected Magento $magento)
    {
    }

    public function update(MagentoCustomerPrice $model): void
    {
        $prices = $model->getDataCollection()->map(function (CustomerPriceData $price) {
            return [
                'customer_id' => $price->customerId,
                'quantity' => $price->quantity,
                'price' => $price->price->getAmount()->toFloat(),
            ];
        })->toArray();

        $response = $this->magento->post('customer-pricing/'.urlencode($model->sku), [
            'customerPrices' => $prices,
        ]);

        if (! $response->ok()) {
            activity()
                ->performedOn($model)
                ->withProperties([
                    'status' => $response->status(),
                    'body' => $response->body(),
                ])
                ->log("Failed to update price for $model->sku");
        }

        $response->throw();
    }

    public static function bind(): void
    {
        app()->singleton(UpdatesMagentoCustomerPrices::class, static::class);
    }
}
