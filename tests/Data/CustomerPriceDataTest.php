<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Data;

use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Helpers\MoneyHelper;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class CustomerPriceDataTest extends TestCase
{
    public function test_setters(): void
    {
        /** @var MoneyHelper $moneyHelper */
        $moneyHelper = app(MoneyHelper::class);

        $data = new CustomerPriceData('::sku:::', $moneyHelper->getMoney(10), 1);

        $data->setSku('::sku_2::');
        $data->setPrice($moneyHelper->getMoney(100));
        $data->setCustomerId(2);
        $data->setQuantity(10);
        $data->setStoreId(1);

        $this->assertEquals([
            'sku' => '::sku_2::',
            'price' => '100.0000',
            'customerId' => 2,
            'quantity' => 10,
            'storeId' => 1,
        ], $data->toArray());
    }
}
