<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Commands;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Commands\ProcessCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Jobs\ProcessCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProcessCustomerPricesCommandTest extends TestCase
{
    #[Test]
    public function it_dispatches_job(): void
    {
        Bus::fake([ProcessCustomerPricesJob::class]);

        $this->artisan(ProcessCustomerPricesCommand::class);

        Bus::assertDispatched(ProcessCustomerPricesJob::class);
    }
}
