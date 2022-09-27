<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Models;

use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class MagentoCustomerPriceTest extends TestCase
{
    public function test_it_registers_error_to_previous_state(): void
    {
        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::create([
            'sku' => '::sku::',
            'state' => MagentoCustomerPrice::STATE_UPDATING,
            'fail_count' => 0,
        ]);

        $model->registerFail(MagentoCustomerPrice::STATE_UPDATE);

        $this->assertEquals(MagentoCustomerPrice::STATE_UPDATE, $model->state);
        $this->assertEquals(1, $model->fail_count);
    }

    public function test_it_registers_error_to_fail_state(): void
    {
        config()->set('magento-customer-prices.fail_count', 5);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::create(['sku' => '::sku::', 'fail_count' => 5]);

        $model->registerFail(MagentoCustomerPrice::STATE_UPDATE);

        $this->assertEquals(MagentoCustomerPrice::STATE_FAILED, $model->state);
        $this->assertFalse($model->sync);
    }
}
