<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions\Retrieval;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Actions\Retrieval\RetrieveAllCustomerPrices;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\RetrieveCustomerPriceJob;
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
        $action->retrieve(null);

        Bus::assertDispatched(RetrieveCustomerPriceJob::class);
    }
}
