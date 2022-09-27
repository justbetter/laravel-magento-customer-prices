<?php

namespace JustBetter\MagentoCustomerPrices\Retriever;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;

abstract class CustomerPriceRetriever
{
    /**
     * Retrieve customer prices for a single sku
     *
     * @return Enumerable<CustomerPriceData>
     */
    abstract public function retrieve(string $sku): Enumerable;

    /**
     * Retrieve all skus that have customer specific prices
     *
     * @return Enumerable<string>
     */
    abstract public function retrieveAllSkus(): Enumerable;

    /**
     * Retrieve an array of skus that have updated prices
     *
     * @return Enumerable<string>
     */
    abstract public function retrieveUpdatedSkus(): Enumerable;
}
