<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions\Retrieval;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Actions\Retrieval\RetrieveAllCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\Fakes\FakeRepository;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use JustBetter\MagentoProducts\Models\MagentoProduct;
use PHPUnit\Framework\Attributes\Test;

class RetrieveAllCustomerPricesTest extends TestCase
{
    #[Test]
    public function it_dispatches_jobs(): void
    {
        config()->set('magento-customer-prices.repository', FakeRepository::class);

        Bus::fake();

        MagentoProduct::query()->create(['sku' => '::sku::', 'exists_in_magento' => true]);

        /** @var RetrieveAllCustomerPrices $action */
        $action = app(RetrieveAllCustomerPrices::class);
        $action->retrieve(null, false);

        Bus::assertDispatched(RetrieveCustomerPriceJob::class);
    }

    #[Test]
    public function it_defers_retrievals(): void
    {
        config()->set('magento-prices.repository', FakeRepository::class);

        Bus::fake();

        MagentoProduct::query()->create(['sku' => '::sku-1::', 'exists_in_magento' => true]);
        MagentoProduct::query()->create(['sku' => '::sku-2::', 'exists_in_magento' => true]);
        MagentoProduct::query()->create(['sku' => '::sku-3::', 'exists_in_magento' => true]);

        CustomerPrice::query()->create(['sku' => '::sku-1::', 'retrieve' => false]);

        /** @var RetrieveAllCustomerPrices $action */
        $action = app(RetrieveAllCustomerPrices::class);
        $action->retrieve(null, true);

        Bus::assertNotDispatched(RetrieveCustomerPriceJob::class);

        $prices = CustomerPrice::query()
            ->where('retrieve', '=', true)
            ->pluck('sku');

        $this->assertEquals([
            '::sku-1::',
            '::sku-2::',
            '::sku-3::',
        ], $prices->toArray());
    }
}
