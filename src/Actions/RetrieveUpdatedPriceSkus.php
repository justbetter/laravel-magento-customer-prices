<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesUpdatedPriceSkus;
use JustBetter\MagentoCustomerPrices\Retriever\CustomerPriceRetriever;

class RetrieveUpdatedPriceSkus implements RetrievesUpdatedPriceSkus
{
    public function retrieve(): Enumerable
    {
        /** @var CustomerPriceRetriever $retriever */
        $retriever = app(config('magento-customer-prices.retriever'));

        return $retriever->retrieveUpdatedSkus();
    }

    public static function bind(): void
    {
        app()->singleton(RetrievesUpdatedPriceSkus::class, static::class);
    }
}
