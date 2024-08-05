<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Fakes;

use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Repository\Repository;

class FakeNullRepository extends Repository
{
    public function retrieve(string $sku): ?CustomerPriceData
    {
        return null;
    }
}
