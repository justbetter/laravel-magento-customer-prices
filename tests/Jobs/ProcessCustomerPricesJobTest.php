<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Jobs;

use JustBetter\MagentoCustomerPrices\Contracts\ProcessesCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\ProcessCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class ProcessCustomerPricesJobTest extends TestCase
{
    #[Test]
    public function it_calls_action(): void
    {
        $this->mock(ProcessesCustomerPrices::class, function (MockInterface $mock): void {
            $mock->shouldReceive('process')->once();
        });

        ProcessCustomerPricesJob::dispatch();
    }
}
