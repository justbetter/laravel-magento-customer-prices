<?php

declare(strict_types=1);

namespace JustBetter\MagentoCustomerPrices\Contracts;

interface ProcessesCustomerPrices
{
    public function process(): void;
}
