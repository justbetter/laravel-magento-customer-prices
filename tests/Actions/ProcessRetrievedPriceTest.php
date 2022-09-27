<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use JustBetter\MagentoCustomerPrices\Actions\ProcessRetrievedPrice;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Helpers\MoneyHelper;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class ProcessRetrievedPriceTest extends TestCase
{
    protected MoneyHelper $moneyHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moneyHelper = app(MoneyHelper::class);
    }

    public function test_it_processes_new_price_status_update(): void
    {
        /** @var ProcessRetrievedPrice $action */
        $action = app(ProcessRetrievedPrice::class);

        $retrievedPriceData = collect([new CustomerPriceData('::sku::', $this->moneyHelper->getMoney(10), 1, 1)]);

        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');
        $this->assertNotNull($model);
        $this->assertNotNull($model->last_retrieved);
        $this->assertEquals(MagentoCustomerPrice::STATE_UPDATE, $model->state);
    }

    public function test_it_processes_existing_price_status_update(): void
    {
        /** @var ProcessRetrievedPrice $action */
        $action = app(ProcessRetrievedPrice::class);

        $retrievedPriceData = collect([new CustomerPriceData('::sku::', $this->moneyHelper->getMoney(10), 1, 1)]);

        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');
        $model->update(['state' => MagentoCustomerPrice::STATE_IDLE]);

        $retrievedPriceData = collect([new CustomerPriceData('::sku::', $this->moneyHelper->getMoney(11), 1, 1)]);
        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');

        $this->assertEquals(MagentoCustomerPrice::STATE_UPDATE, $model->state);
    }

    public function test_it_processes_existing_not_modified(): void
    {
        /** @var ProcessRetrievedPrice $action */
        $action = app(ProcessRetrievedPrice::class);

        $retrievedPriceData = collect([new CustomerPriceData('::sku::', $this->moneyHelper->getMoney(10), 1, 1)]);

        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');
        $model->update(['state' => MagentoCustomerPrice::STATE_IDLE, 'last_updated' => now()]);

        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');

        $this->assertEquals(MagentoCustomerPrice::STATE_IDLE, $model->state);
    }

    public function test_it_processes_existing_not_modified_force(): void
    {
        /** @var ProcessRetrievedPrice $action */
        $action = app(ProcessRetrievedPrice::class);

        $retrievedPriceData = collect([new CustomerPriceData('::sku::', $this->moneyHelper->getMoney(10), 1, 1)]);

        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');
        $model->update(['state' => MagentoCustomerPrice::STATE_IDLE, 'last_updated' => now()]);

        $action->process('::sku::', $retrievedPriceData, true);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');

        $this->assertEquals(MagentoCustomerPrice::STATE_UPDATE, $model->state);
    }

    public function test_it_does_nothing_when_empty(): void
    {
        /** @var ProcessRetrievedPrice $action */
        $action = app(ProcessRetrievedPrice::class);

        $retrievedPriceData = collect();

        $action->process('::sku::', $retrievedPriceData);

        /** @var MagentoCustomerPrice $model */
        $model = MagentoCustomerPrice::findBySku('::sku::');

        $this->assertNull($model);
    }
}
