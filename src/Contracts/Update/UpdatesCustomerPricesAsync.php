<?php

namespace JustBetter\MagentoCustomerPrices\Contracts\Update;

use Illuminate\Support\Collection;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

interface UpdatesCustomerPricesAsync
{
    /** @param Collection<int, CustomerPrice> $customerPrices */
    public function update(Collection $customerPrices): void;
}
