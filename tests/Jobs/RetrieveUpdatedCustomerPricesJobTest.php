<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesUpdatedPriceSkus;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveUpdatedCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class RetrieveUpdatedCustomerPricesJobTest extends TestCase
{
    public function test_it_calls_action_and_dispatches_job(): void
    {
        Bus::fake([RetrieveCustomerPriceJob::class]);

        $this->mock(RetrievesUpdatedPriceSkus::class, function (MockInterface $mock) {
            $mock->shouldReceive('retrieve')->andReturn(collect(['::sku_1::', '::sku_2::']));
        });

        RetrieveUpdatedCustomerPricesJob::dispatchSync();

        Bus::assertDispatchedTimes(RetrieveCustomerPriceJob::class, 2);
    }
}
