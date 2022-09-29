<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesAllCustomerPriceSkus;
use JustBetter\MagentoCustomerPrices\Retriever\CustomerPriceRetriever;

class RetrieveAllCustomerPriceSkus implements RetrievesAllCustomerPriceSkus
{
    public function retrieve(): Enumerable
    {
        /** @var CustomerPriceRetriever $retriever */
        $retriever = app(config('magento-customer-prices.retriever'));

        return $retriever->retrieveAllSkus();
    }

    public static function bind(): void
    {
        app()->singleton(RetrievesAllCustomerPriceSkus::class, static::class);
    }
}
