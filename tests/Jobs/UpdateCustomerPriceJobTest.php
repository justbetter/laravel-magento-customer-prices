<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs;

use Exception;
use JustBetter\ErrorLogger\Models\Error;
use JustBetter\MagentoCustomerPrices\Contracts\UpdatesPrices;
use JustBetter\MagentoCustomerPrices\Jobs\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class UpdateCustomerPriceJobTest extends TestCase
{
    public function test_it_calls_action(): void
    {
        $this->mock(UpdatesPrices::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->with('::sku::')
                ->once();
        });

        UpdateCustomerPriceJob::dispatchSync('::sku::');
    }

    public function test_it_has_unique_id_and_tags(): void
    {
        $job = new UpdateCustomerPriceJob('::sku::');

        $this->assertEquals('::sku::', $job->uniqueId());
        $this->assertEquals(['::sku::'], $job->tags());
    }

    public function test_it_registers_failure(): void
    {
        $job = new UpdateCustomerPriceJob('::sku::');

        $job->failed(new Exception('::test::'));
        $this->assertCount(1, Error::all());
    }
}
