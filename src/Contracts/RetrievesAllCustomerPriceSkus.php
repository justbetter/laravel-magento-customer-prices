<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

use Illuminate\Support\Enumerable;

interface RetrievesAllCustomerPriceSkus
{
    public function retrieve(): Enumerable;
}
