<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs;

use Exception;
use JustBetter\ErrorLogger\Models\Error;
use JustBetter\MagentoCustomerPrices\Contracts\ProcessesRetrievedPrice;
use JustBetter\MagentoCustomerPrices\Contracts\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class RetrieveCustomerPriceJobTest extends TestCase
{
    public function test_it_calls_actions(): void
    {
        $data = collect(['data']);
        $this->mock(RetrievesCustomerPrice::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('retrieve')
                ->with('::sku::')
                ->andReturn($data)
                ->once();
        });

        $this->mock(ProcessesRetrievedPrice::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('process')
                ->with('::sku::', $data, false)
                ->once();
        });

        RetrieveCustomerPriceJob::dispatchSync('::sku::');
    }

    public function test_it_has_unique_id_and_tags(): void
    {
        $job = new RetrieveCustomerPriceJob('::sku::');

        $this->assertEquals('::sku::', $job->uniqueId());
        $this->assertEquals(['::sku::'], $job->tags());
    }

    public function test_it_registers_failure(): void
    {
        $job = new RetrieveCustomerPriceJob('::sku::');

        $job->failed(new Exception('::test::'));
        $this->assertCount(1, Error::all());
    }
}
