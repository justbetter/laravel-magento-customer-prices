<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;

interface DeterminesPricesModified
{
    /**
     * @param  Enumerable<CustomerPriceData>  $a
     * @param  Enumerable<CustomerPriceData>  $b
     */
    public function check(Enumerable $a, Enumerable $b): bool;
}
