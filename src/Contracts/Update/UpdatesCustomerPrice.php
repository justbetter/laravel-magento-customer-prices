<?php

namespace JustBetter\MagentoCustomerPrices\Contracts\Update;

use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;

interface UpdatesCustomerPrice
{
    public function update(CustomerPrice $price): void;
}
