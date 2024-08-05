<?php

namespace JustBetter\MagentoCustomerPrices\Contracts\Retrieval;

use Illuminate\Support\Carbon;

interface RetrievesAllCustomerPrices
{
    public function retrieve(?Carbon $from): void;
}
