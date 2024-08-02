<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions\Retrieval;

use Illuminate\Support\Facades\Bus;
use JustBetter\MagentoCustomerPrices\Actions\Retrieval\RetrieveCustomerPrice;
use JustBetter\MagentoCustomerPrices\Jobs\Retrieval\SaveCustomerPriceJob;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\Fakes\FakeNullRepository;
use JustBetter\MagentoCustomerPrices\Tests\Fakes\FakeRepository;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RetrieveCustomerPriceTest extends TestCase
{
    #[Test]
    public function it_sets_retrieve_when_no_pricedata(): void
    {
        config()->set('magento-customer-prices.repository', FakeNullRepository::class);

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()
            ->create([
                'sku' => '::sku::',
                'retrieve' => true,
                'prices' => [],
            ]);

        /** @var RetrieveCustomerPrice $action */
        $action = app(RetrieveCustomerPrice::class);
        $action->retrieve('::sku::', false);

        $this->assertFalse($model->refresh()->retrieve);
    }

    #[Test]
    public function it_dispatches_save_job(): void
    {
        config()->set('magento-customer-prices.repository', FakeRepository::class);
        Bus::fake();

        CustomerPrice::query()
            ->create([
                'sku' => '::sku::',
                'prices' => [],
            ]);

        /** @var RetrieveCustomerPrice $action */
        $action = app(RetrieveCustomerPrice::class);
        $action->retrieve('::sku::', true);

        Bus::assertDispatched(SaveCustomerPriceJob::class, function (SaveCustomerPriceJob $job): bool {
            return $job->data['sku'] === '::sku::' && $job->forceUpdate;
        });
    }
}
