<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs\Retrieval;

use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\SavesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\SaveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class SaveCustomerPriceJobTest extends TestCase
{
    #[Test]
    public function it_calls_action(): void
    {
        $this->mock(SavesCustomerPrice::class, function (MockInterface $mock): void {
            $mock->shouldReceive('save')->once();
        });

        $priceData = CustomerPriceData::of(['sku' => '::sku::']);

        SaveCustomerPriceJob::dispatch($priceData, false);
    }

    #[Test]
    public function it_has_unique_id(): void
    {
        $priceData = CustomerPriceData::of(['sku' => '::sku::']);

        $job = new SaveCustomerPriceJob($priceData, false);

        $this->assertEquals('::sku::', $job->uniqueId());
    }

    #[Test]
    public function it_has_tags(): void
    {
        $priceData = CustomerPriceData::of(['sku' => '::sku::']);

        $job = new SaveCustomerPriceJob($priceData, false);

        $this->assertEquals(['::sku::'], $job->tags());
    }
}
