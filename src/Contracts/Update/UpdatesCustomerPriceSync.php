<?php

namespace JustBetter\MagentoCustomerPrices\Contracts\Update;

use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

interface UpdatesCustomerPriceSync
{
    public function update(CustomerPrice $price): void;
}
