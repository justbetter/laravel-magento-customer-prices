<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoCustomerPrices\Retriever\CustomerPriceRetriever;

class RetrieveCustomerPrice implements RetrievesCustomerPrice
{
    public function retrieve(string $sku): Enumerable
    {
        $model = MagentoCustomerPrice::findBySku($sku);

        if ($model !== null) {
            $model->setState(MagentoCustomerPrice::STATE_RETRIEVING);
        }

        /** @var CustomerPriceRetriever $retriever */
        $retriever = app(config('magento-customer-prices.retriever'));

        return $retriever->retrieve($sku);
    }

    public static function bind(): void
    {
        app()->singleton(RetrievesCustomerPrice::class, static::class);
    }
}
