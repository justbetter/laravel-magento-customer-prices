<?php

namespace JustBetter\MagentoCustomerPrices\Contracts\Retrieval;

interface RetrievesCustomerPrice
{
    public function retrieve(string $sku, bool $forceUpdate): void;
}
