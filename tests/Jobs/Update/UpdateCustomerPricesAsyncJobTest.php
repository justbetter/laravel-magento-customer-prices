<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs\Update;

use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPricesAsync;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPricesAsyncJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class UpdateCustomerPricesAsyncJobTest extends TestCase
{
    #[Test]
    public function it_calls_action(): void
    {
        $this->mock(UpdatesCustomerPricesAsync::class, function (MockInterface $mock): void {
            $mock->shouldReceive('update')->once();
        });

        UpdateCustomerPricesAsyncJob::dispatch(collect());
    }

    #[Test]
    public function it_has_tags(): void
    {
        $prices = collect([
            CustomerPrice::query()->create(['sku' => '::sku_1::']),
            CustomerPrice::query()->create(['sku' => '::sku_2::']),
        ]);
        $job = new UpdateCustomerPricesAsyncJob($prices);

        $this->assertEquals(['::sku_1::', '::sku_2::'], $job->tags());
    }
}
