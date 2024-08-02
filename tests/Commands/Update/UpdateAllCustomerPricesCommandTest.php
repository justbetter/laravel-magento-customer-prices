<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Commands\Update;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Commands\Update\UpdateAllCustomerPricesCommand;
use JustBetter\MagentoCustomerPrices\Jobs\Update\UpdateCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use JustBetter\MagentoProducts\Models\MagentoProduct;
use PHPUnit\Framework\Attributes\Test;

class UpdateAllCustomerPricesCommandTest extends TestCase
{
    #[Test]
    public function it_dispatches_jobs(): void
    {
        Bus::fake([UpdateCustomerPriceJob::class]);

        MagentoProduct::query()->create(['sku' => '::sku_1::', 'exists_in_magento' => true]);
        MagentoProduct::query()->create(['sku' => '::sku_2::', 'exists_in_magento' => false]);
        MagentoProduct::query()->create(['sku' => '::sku_3::', 'exists_in_magento' => true]);

        CustomerPrice::query()->create(['sku' => '::sku_1::', 'prices' => []]);
        CustomerPrice::query()->create(['sku' => '::sku_2::', 'prices' => []]);
        CustomerPrice::query()->create(['sku' => '::sku_3::', 'prices' => []]);
        CustomerPrice::query()->create(['sku' => '::sku_4::', 'prices' => []]);

        $this->artisan(UpdateAllCustomerPricesCommand::class);

        Bus::assertDispatchedTimes(UpdateCustomerPriceJob::class, 2);
    }
}
