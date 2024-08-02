<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Exception;
use JustBetter\MagentoCustomerPrices\Actions\UpdatePrices;
use JustBetter\MagentoCustomerPrices\Contracts\UpdatesMagentoCustomerPrices;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use JustBetter\MagentoProducts\Contracts\ChecksMagentoExistence;
use Mockery\MockInterface;

class UpdatePricesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        CustomerPrice::create(['sku' => '::sku::']);
    }

    public function test_it_returns_when_no_price_exists(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->with('::sku::')->andReturnTrue();
        });

        $this->mock(UpdatesMagentoCustomerPrices::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('update');
        });

        /** @var UpdatePrices $action */
        $action = app(UpdatePrices::class);

        $action->update('::sku_does_not_exist::');
    }

    public function test_it_returns_when_not_exists_in_magento(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->with('::sku::')->andReturnFalse();
        });

        $this->mock(UpdatesMagentoCustomerPrices::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('update');
        });

        /** @var UpdatePrices $action */
        $action = app(UpdatePrices::class);

        $action->update('::sku_does_not_exist::');
    }

    public function test_it_sets_status_to_updating(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->with('::sku::')->andReturnTrue();
        });

        $this->mock(UpdatesMagentoCustomerPrices::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andThrow(Exception::class);
        });

        /** @var UpdatePrices $action */
        $action = app(UpdatePrices::class);

        $this->expectException(Exception::class);

        $action->update('::sku::');

        /** @var CustomerPrice $model */
        $model = CustomerPrice::findBySku('::sku::');
        $this->assertEquals(CustomerPrice::STATE_UPDATING, $model->state);
    }

    public function test_it_sets_status_to_idle(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->with('::sku::')->andReturnTrue();
        });

        $this->mock(UpdatesMagentoCustomerPrices::class, function (MockInterface $mock) {
            $mock->shouldReceive('update');
        });

        /** @var UpdatePrices $action */
        $action = app(UpdatePrices::class);

        $action->update('::sku::');

        /** @var CustomerPrice $model */
        $model = CustomerPrice::findBySku('::sku::');
        $this->assertEquals(CustomerPrice::STATE_IDLE, $model->state);
    }

    public function test_disable_sync_when_not_exists(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->with('::sku::')->andReturnFalse();
        });

        $this->mock(UpdatesMagentoCustomerPrices::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('update');
        });

        /** @var UpdatePrices $action */
        $action = app(UpdatePrices::class);

        $action->update('::sku::');

        /** @var CustomerPrice $model */
        $model = CustomerPrice::findBySku('::sku::');
        $this->assertFalse($model->sync);
    }
}
