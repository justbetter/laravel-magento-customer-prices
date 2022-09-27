<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

interface UpdatesPrices
{
    public function update(string $sku): void;
}
