<?php

namespace JustBetter\MagentoCustomerPrices\Contracts;

interface RunsCustomerPriceSync
{
    public function sync(): void;
}
