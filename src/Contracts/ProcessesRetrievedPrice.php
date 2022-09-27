<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

use Illuminate\Support\Enumerable;

interface ProcessesRetrievedPrice
{
    public function process(string $sku, Enumerable $priceData, bool $forceUpdate = false): void;
}
