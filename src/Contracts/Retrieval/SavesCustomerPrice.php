<?php

namespace JustBetter\MagentoCustomerPrices\Contracts\Retrieval;

use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;

interface SavesCustomerPrice
{
    public function save(CustomerPriceData $customerPriceData, bool $forceUpdate): void;
}
