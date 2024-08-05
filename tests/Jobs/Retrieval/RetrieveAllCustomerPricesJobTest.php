<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs\Retrieval;

use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesCustomerPrice;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class RetrieveAllCustomerPricesJobTest extends TestCase
{
    #[Test]
    public function it_calls_action(): void
    {
        $this->mock(RetrievesCustomerPrice::class, function (MockInterface $mock): void {
            $mock->shouldReceive('retrieve')->once();
        });

        RetrieveCustomerPriceJob::dispatch('::sku::', false);
    }

    #[Test]
    public function it_has_unique_id(): void
    {
        $job = new RetrieveCustomerPriceJob('::sku::', false);

        $this->assertEquals('::sku::', $job->uniqueId());
    }

    #[Test]
    public function it_has_tags(): void
    {
        $job = new RetrieveCustomerPriceJob('::sku::', false);

        $this->assertEquals(['::sku::'], $job->tags());
    }
}
