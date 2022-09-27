<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs;

use JustBetter\MagentoCustomerPrices\Contracts\RunsCustomerPriceSync;
use JustBetter\MagentoCustomerPrices\Jobs\SyncCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class SyncCustomerPricesJobTest extends TestCase
{
    public function test_it_calls_action(): void
    {
        $this->mock(RunsCustomerPriceSync::class, function (MockInterface $mock) {
            $mock->shouldReceive('sync')->once();
        });

        SyncCustomerPricesJob::dispatchSync();
    }
}
