<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoClient\Contracts\ChecksMagento;
use JustBetter\MagentoCustomerPrices\Actions\ProcessCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class ProcessCustomerPricesTest extends TestCase
{
    #[Test]
    public function it_dispatches_retrieval_jobs(): void
    {
        Bus::fake();

        CustomerPrice::query()->create([
            'sku' => '::sku::',
            'retrieve' => true,
            'prices' => [],
        ]);

        /** @var ProcessCustomerPrices $action */
        $action = app(ProcessCustomerPrices::class);
        $action->process();

        Bus::assertDispatched(RetrieveCustomerPriceJob::class);
        Bus::assertNotDispatched(UpdateCustomerPriceJob::class);
    }

    #[Test]
    public function it_dispatches_update_jobs(): void
    {
        Bus::fake();

        CustomerPrice::query()->create([
            'sku' => '::sku::',
            'update' => true,
            'prices' => [],
        ]);

        /** @var ProcessCustomerPrices $action */
        $action = app(ProcessCustomerPrices::class);
        $action->process();

        Bus::assertNotDispatched(RetrieveCustomerPriceJob::class);
        Bus::assertDispatched(UpdateCustomerPriceJob::class);
    }

    #[Test]
    public function it_does_not_dispatch_update_jobs_if_magento_is_unavailable(): void
    {
        Bus::fake();

        $this->mock(ChecksMagento::class, function (MockInterface $mock): void {
            $mock->shouldReceive('available')->andReturnFalse();
        });

        CustomerPrice::query()->create([
            'sku' => '::sku::',
            'update' => true,
            'prices' => [],
        ]);

        /** @var ProcessCustomerPrices $action */
        $action = app(ProcessCustomerPrices::class);
        $action->process();

        Bus::assertNotDispatched(UpdateCustomerPriceJob::class);
    }
}
