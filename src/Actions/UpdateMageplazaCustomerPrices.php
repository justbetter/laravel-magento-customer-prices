<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use JustBetter\MagentoClient\Client\Magento;
use JustBetter\MagentoCustomerPrices\Contracts\UpdatesMagentoCustomerPrices;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;

class UpdateMageplazaCustomerPrices implements UpdatesMagentoCustomerPrices
{
    public function __construct(protected Magento $magento)
    {
    }

    public function update(MagentoCustomerPrice $model): void
    {
        $recordId = 0;
        $mageplazaData = $model->getDataCollection()->map(function (CustomerPriceData $price) use(&$recordId) {
            $data = $price->toMageplazaData();

            // Retrieve the customer name from Magento
            $customer = $this->magento->get('customers/'.$price->getCustomerId())->json();

            $data['customer'] = implode(' ', [$customer['firstname'], $customer['lastname']]);
            $data['record_id'] = $recordId;

            $recordId++;

            return $data;
        })->toArray();

        $response = $this->magento->put("products/$model->sku", [
            'product' => [
                'custom_attributes' => [
                    [
                        'attribute_code' => 'mp_specific_customer',
                        'value' => json_encode($mageplazaData),
                    ],
                ],
            ],
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
