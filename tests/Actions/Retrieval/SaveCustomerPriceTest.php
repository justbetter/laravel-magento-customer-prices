<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions\Retrieval;

use JustBetter\MagentoCustomerPrices\Actions\Retrieval\SaveCustomerPrice;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SaveCustomerPriceTest extends TestCase
{
    #[Test]
    public function it_saves_fields(): void
    {
        $priceData = CustomerPriceData::of([
            'sku' => '::sku::',
            'prices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);

        /** @var SaveCustomerPrice $action */
        $action = app(SaveCustomerPrice ::class);
        $action->save($priceData, false);

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->firstWhere('sku', '=', '::sku::');

        $this->assertEquals([['customer_id' => 1, 'price' => 10, 'quantity' => 1]], $model->prices);

        $this->assertTrue($model->sync);
        $this->assertFalse($model->retrieve);
        $this->assertTrue($model->update);
        $this->assertNotNull($model->last_retrieved);
        $this->assertEquals('b7330ec7904538aab484f89e78efe836', $model->checksum);
    }

    #[Test]
    public function it_does_not_set_update_when_unchanged(): void
    {
        $priceData = CustomerPriceData::of([
            'sku' => '::sku::',
            'prices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);

        /** @var SaveCustomerPrice $action */
        $action = app(SaveCustomerPrice::class);
        $action->save($priceData, false);

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->firstWhere('sku', '=', '::sku::');

        $this->assertTrue($model->update);

        $model->update(['update' => false]);

        $action->save($priceData, false);

        $this->assertFalse($model->refresh()->update);
    }

    #[Test]
    public function it_can_force_update(): void
    {
        $priceData = CustomerPriceData::of([
            'sku' => '::sku::',
            'prices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);

        /** @var SaveCustomerPrice $action */
        $action = app(SaveCustomerPrice::class);
        $action->save($priceData, false);

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->firstWhere('sku', '=', '::sku::');

        $this->assertTrue($model->update);

        $model->update(['update' => false]);

        $action->save($priceData, true);

        $this->assertTrue($model->refresh()->update);
    }
}
