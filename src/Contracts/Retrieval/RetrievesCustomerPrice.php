<?php

declare(strict_types=1);

namespace JustBetter\MagentoCustomerPrices\Contracts\Retrieval;

interface RetrievesCustomerPrice
{
    public function retrieve(string $sku, bool $forceUpdate): void;
}
