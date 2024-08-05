<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Commands\Update;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Commands\Update\UpdateCustomerPriceCommand;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UpdateCustomerPriceCommandTest extends TestCase
{
    #[Test]
    public function it_dispatches_job(): void
    {
        Bus::fake([UpdateCustomerPriceJob::class]);

        CustomerPrice::query()->create(['sku' => '::sku_1::', 'prices' => []]);

        $this->artisan(UpdateCustomerPriceCommand::class, [
            'sku' => '::sku_1::',
        ]);

        Bus::assertDispatched(UpdateCustomerPriceJob::class);
    }

    #[Test]
    public function it_throws_exception_on_missing_price(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->artisan(UpdateCustomerPriceCommand::class, [
            'sku' => '::some-non-existent-sku::',
        ]);
    }
}
