<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Commands\Retrieval;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Commands\Retrieval\RetrieveCustomerPriceCommand;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RetrieveCustomerPriceCommandTest extends TestCase
{
    #[Test]
    public function it_dispatches_job(): void
    {
        Bus::fake([RetrieveCustomerPriceJob::class]);

        $this->artisan(RetrieveCustomerPriceCommand::class, ['sku' => '::sku::']);

        Bus::assertDispatched(RetrieveCustomerPriceJob::class);
    }
}
