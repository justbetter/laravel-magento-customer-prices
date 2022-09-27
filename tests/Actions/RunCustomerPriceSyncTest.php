<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Actions\RunCustomerPriceSync;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class RunCustomerPriceSyncTest extends TestCase
{
    protected RunCustomerPriceSync $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(RunCustomerPriceSync::class);
    }

    public function test_it_dispatches_retrieve_jobs(): void
    {
        Bus::fake();

        MagentoCustomerPrice::create(['sku' => '::sku_1::', 'state' => MagentoCustomerPrice::STATE_RETRIEVE]);
        MagentoCustomerPrice::create(['sku' => '::sku_2::', 'state' => MagentoCustomerPrice::STATE_RETRIEVE]);
        MagentoCustomerPrice::create(['sku' => '::sku_3::', 'state' => MagentoCustomerPrice::STATE_IDLE]);

        $this->action->sync();

        Bus::assertDispatchedTimes(RetrieveCustomerPriceJob::class, 2);
        Bus::assertDispatched(RetrieveCustomerPriceJob::class, function (RetrieveCustomerPriceJob $job) {
            return in_array($job->sku, ['::sku_1::', '::sku_2::']);
        });
    }

    public function test_it_dispatches_update_jobs(): void
    {
        Bus::fake();

        MagentoCustomerPrice::create(['sku' => '::sku_1::', 'state' => MagentoCustomerPrice::STATE_UPDATE]);
        MagentoCustomerPrice::create(['sku' => '::sku_2::', 'state' => MagentoCustomerPrice::STATE_RETRIEVE]);
        MagentoCustomerPrice::create(['sku' => '::sku_3::', 'state' => MagentoCustomerPrice::STATE_UPDATE]);
        MagentoCustomerPrice::create(['sku' => '::sku_4::', 'state' => MagentoCustomerPrice::STATE_FAILED]);

        $this->action->sync();

        Bus::assertDispatchedTimes(UpdateCustomerPriceJob::class, 2);
        Bus::assertDispatched(UpdateCustomerPriceJob::class, function (UpdateCustomerPriceJob $job) {
            return in_array($job->sku, ['::sku_1::', '::sku_3::']);
        });
    }
}
