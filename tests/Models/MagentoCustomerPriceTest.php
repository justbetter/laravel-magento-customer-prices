<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Models;

use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class MagentoCustomerPriceTest extends TestCase
{
    public function test_it_registers_error_to_previous_state(): void
    {
        /** @var CustomerPrice $model */
        $model = CustomerPrice::create([
            'sku' => '::sku::',
            'state' => CustomerPrice::STATE_UPDATING,
            'fail_count' => 0,
        ]);

        $model->registerFail(CustomerPrice::STATE_UPDATE);

        $this->assertEquals(CustomerPrice::STATE_UPDATE, $model->state);
        $this->assertEquals(1, $model->fail_count);
    }

    public function test_it_registers_error_to_fail_state(): void
    {
        config()->set('magento-customer-prices.fail_count', 5);

        /** @var CustomerPrice $model */
        $model = CustomerPrice::create(['sku' => '::sku::', 'fail_count' => 5]);

        $model->registerFail(CustomerPrice::STATE_UPDATE);

        $this->assertEquals(CustomerPrice::STATE_FAILED, $model->state);
        $this->assertFalse($model->sync);
    }
}
