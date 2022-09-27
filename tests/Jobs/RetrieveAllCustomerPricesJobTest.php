<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs;

use Exception;
use Illuminate\Support\Facades\Bus;
use JustBetter\ErrorLogger\Models\Error;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesAllCustomerPriceSkus;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveAllCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class RetrieveAllCustomerPricesJobTest extends TestCase
{
    public function test_it_calls_action_and_dispatches_job(): void
    {
        Bus::fake([RetrieveCustomerPriceJob::class]);

        $this->mock(RetrievesAllCustomerPriceSkus::class, function (MockInterface $mock) {
            $mock->shouldReceive('retrieve')->andReturn(collect(['::sku_1::', '::sku_2::']));
        });

        RetrieveAllCustomerPricesJob::dispatchSync();

        Bus::assertDispatchedTimes(RetrieveCustomerPriceJob::class, 2);
    }

    public function test_failed_logs_error(): void
    {
        $job = new RetrieveAllCustomerPricesJob();

        $job->failed(new Exception('::test::'));

        $this->assertCount(1, Error::all());
    }
}
