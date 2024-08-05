<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs\Update;

use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class UpdateCustomerPriceJobTest extends TestCase
{
    #[Test]
    public function it_calls_action(): void
    {
        $this->mock(UpdatesCustomerPrice::class, function (MockInterface $mock): void {
            $mock->shouldReceive('update')->once();
        });

        /** @var CustomerPrice $price */
        $price = CustomerPrice::query()->create(['sku' => '::sku::', 'prices' => []]);

        UpdateCustomerPriceJob::dispatch($price);
    }

    #[Test]
    public function it_has_unique_id(): void
    {
        /** @var CustomerPrice $price */
        $price = CustomerPrice::query()->create(['sku' => '::sku::', 'prices' => []]);

        $job = new UpdateCustomerPriceJob($price);

        $this->assertEquals($price->id, $job->uniqueId());
    }

    #[Test]
    public function it_has_tags(): void
    {
        /** @var CustomerPrice $price */
        $price = CustomerPrice::query()->create(['sku' => '::sku::', 'prices' => []]);

        $job = new UpdateCustomerPriceJob($price);

        $this->assertEquals(['::sku::'], $job->tags());
    }
}
