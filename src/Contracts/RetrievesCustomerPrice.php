<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

use Illuminate\Support\Enumerable;

interface RetrievesCustomerPrice
{
    public function retrieve(string $sku): Enumerable;
}
