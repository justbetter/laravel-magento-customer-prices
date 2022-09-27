<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;

interface UpdatesMagentoCustomerPrices
{
    public function update(MagentoCustomerPrice $model): void;
}
