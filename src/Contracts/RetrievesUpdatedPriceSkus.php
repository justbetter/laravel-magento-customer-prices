<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

use Illuminate\Support\Enumerable;

interface RetrievesUpdatedPriceSkus
{
    public function retrieve(): Enumerable;
}
