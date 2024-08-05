<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Commands\Retrieval;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Commands\Retrieval\RetrieveAllCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveAllCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RetrieveAllCustomerPricesCommandTest extends TestCase
{
    #[Test]
    public function it_dispatches_job(): void
    {
        Bus::fake([RetrieveAllCustomerPricesJob::class]);

        $this->artisan(RetrieveAllCustomerPricesCommand::class);

        Bus::assertDispatched(RetrieveAllCustomerPricesJob::class);
    }
}
