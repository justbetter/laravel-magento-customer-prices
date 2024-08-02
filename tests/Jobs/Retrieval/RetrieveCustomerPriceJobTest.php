<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs\Retrieval;

use JustBetter\MagentoCustomerPrices\Contracts\Retrieval\RetrievesAllCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveAllCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class RetrieveCustomerPriceJobTest extends TestCase
{
    #[Test]
    public function it_calls_action(): void
    {
        $this->mock(RetrievesAllCustomerPrices::class, function (MockInterface $mock): void {
            $mock->shouldReceive('retrieve')->once();
        });

        RetrieveAllCustomerPricesJob::dispatch();
    }
}
