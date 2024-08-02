<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Actions\ProcessCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class RunCustomerPriceSyncTest extends TestCase
{
    protected ProcessCustomerPrices $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(ProcessCustomerPrices::class);
    }

    public function test_it_dispatches_retrieve_jobs(): void
    {
        Bus::fake();

        CustomerPrice::create(['sku' => '::sku_1::', 'state' => CustomerPrice::STATE_RETRIEVE]);
        CustomerPrice::create(['sku' => '::sku_2::', 'state' => CustomerPrice::STATE_RETRIEVE]);
        CustomerPrice::create(['sku' => '::sku_3::', 'state' => CustomerPrice::STATE_IDLE]);

        $this->action->sync();

        Bus::assertDispatchedTimes(RetrieveCustomerPriceJob::class, 2);
        Bus::assertDispatched(RetrieveCustomerPriceJob::class, function (RetrieveCustomerPriceJob $job) {
            return in_array($job->sku, ['::sku_1::', '::sku_2::']);
        });
    }

    public function test_it_dispatches_update_jobs(): void
    {
        Bus::fake();

        CustomerPrice::create(['sku' => '::sku_1::', 'state' => CustomerPrice::STATE_UPDATE]);
        CustomerPrice::create(['sku' => '::sku_2::', 'state' => CustomerPrice::STATE_RETRIEVE]);
        CustomerPrice::create(['sku' => '::sku_3::', 'state' => CustomerPrice::STATE_UPDATE]);
        CustomerPrice::create(['sku' => '::sku_4::', 'state' => CustomerPrice::STATE_FAILED]);

        $this->action->sync();

        Bus::assertDispatchedTimes(UpdateCustomerPriceJob::class, 2);
        Bus::assertDispatched(UpdateCustomerPriceJob::class, function (UpdateCustomerPriceJob $job) {
            return in_array($job->sku, ['::sku_1::', '::sku_3::']);
        });
    }
}
