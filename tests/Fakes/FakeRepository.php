<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Fakes;

use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Repository\Repository;

class FakeRepository extends Repository
{
    public function retrieve(string $sku): ?CustomerPriceData
    {
        return CustomerPriceData::of([
            'sku' => $sku,
            'prices' => [
                [
                    'price' => 10,
                    'customer_id' => 1,
                    'quantity' => 1,
                ],
            ],
        ]);
    }
}
