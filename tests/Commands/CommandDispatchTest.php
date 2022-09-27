<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Commands;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Commands\RetrieveAllCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\RetrieveCustomerPriceCommand;
use JustBetter\MagentoCustomerPrices\Commands\RetrieveUpdatedCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\SyncCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Commands\UpdateCustomerPriceCommand;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveAllCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\RetrieveUpdatedCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Jobs\SyncCustomerPricesJob;
use JustBetter\MagentoCustomerPrices\Jobs\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class CommandDispatchTest extends TestCase
{
    /** @dataProvider provider */
    public function test_it_dispatches_job(string $command, string $job, array $params = []): void
    {
        Bus::fake([$job]);

        $this->artisan($command, $params);

        Bus::assertDispatched($job);
    }

    public function provider(): array
    {
        return [
            [
                'command' => RetrieveAllCustomerPricesCommand::class,
                'job' => RetrieveAllCustomerPricesJob::class,
            ],
            [
                'command' => RetrieveUpdatedCustomerPricesCommand::class,
                'job' => RetrieveUpdatedCustomerPricesJob::class,
            ],
            [
                'command' => SyncCustomerPricesCommand::class,
                'job' => SyncCustomerPricesJob::class,
            ],
            [
                'command' => RetrieveCustomerPriceCommand::class,
                'job' => RetrieveCustomerPriceJob::class,
                'params' => ['sku' => '::sku::'],
            ],
            [
                'command' => UpdateCustomerPriceCommand::class,
                'job' => UpdateCustomerPriceJob::class,
                'params' => ['sku' => '::sku::'],
            ],
        ];
    }
}
