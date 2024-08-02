<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Retrieval;

use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\SavesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

class SaveCustomerPrice implements SavesCustomerPrice
{
    public function save(CustomerPriceData $customerPriceData, bool $forceUpdate): void
    {
        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->firstOrNew([
            'sku' => $customerPriceData['sku'],
        ]);

        $model->prices = $customerPriceData['prices'];

        $model->sync = true;
        $model->retrieve = false;
        $model->last_retrieved = now();

        $model->update = $forceUpdate || $model->checksum !== $customerPriceData->checksum();
        $model->checksum = $customerPriceData->checksum();

        $model->save();
    }

    public static function bind(): void
    {
        app()->singleton(SavesCustomerPrice::class, static::class);
    }
}
