<?php

declare(strict_types=1);

namespace JustBetter\MagentoCustomerPrices\Tests\Actions\Update;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use JustBetter\MagentoClient\Client\Magento;
use JustBetter\MagentoCustomerPrices\Actions\Update\UpdateCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use JustBetter\MagentoProducts\Contracts\ChecksMagentoExistence;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

final class UpdateCustomerPriceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Magento::fake();
    }

    #[Test]
    public function it_updates_customer_prices(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock): void {
            $mock->shouldReceive('exists')->andReturnTrue();
        });

        Http::fake([
            'magento/rest/all/V1/customer-pricing/%3A%3Asku%3A%3A' => Http::response(),
        ])->preventStrayRequests();

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->create([
            'sku' => '::sku::',
            'fail_count' => 1,
            'last_failed' => now(),
            'prices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);

        /** @var UpdateCustomerPrice $action */
        $action = app(UpdateCustomerPrice::class);
        $action->update($model);

        $model->refresh();

        $this->assertFalse($model->update);
        $this->assertInstanceOf(Carbon::class, $model->last_updated);
        $this->assertEquals(0, $model->fail_count);
        $this->assertNotInstanceOf(Carbon::class, $model->last_failed);

        Http::assertSent(fn (Request $request): bool => $request->data() === [
            'customerPrices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);
    }

    #[Test]
    public function it_does_nothing_if_product_does_not_exist_in_magento(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock): void {
            $mock->shouldReceive('exists')->andReturnFalse();
        });

        Http::fake()->preventStrayRequests();

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->create([
            'sku' => '::sku::',
            'prices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);

        /** @var UpdateCustomerPrice $action */
        $action = app(UpdateCustomerPrice::class);
        $action->update($model);

        $model->refresh();

        $this->assertFalse($model->update);
        $this->assertNotInstanceOf(Carbon::class, $model->last_updated);

        Http::assertNothingSent();
    }

    #[Test]
    public function it_handles_failure(): void
    {
        $this->mock(ChecksMagentoExistence::class, function (MockInterface $mock): void {
            $mock->shouldReceive('exists')->andReturnTrue();
        });

        Http::fake([
            'magento/rest/all/V1/customer-pricing/%3A%3Asku%3A%3A' => Http::response(null, 500),
        ])->preventStrayRequests();

        /** @var CustomerPrice $model */
        $model = CustomerPrice::query()->create([
            'sku' => '::sku::',
            'prices' => [
                [
                    'customer_id' => 1,
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ]);

        /** @var UpdateCustomerPrice $action */
        $action = app(UpdateCustomerPrice::class);

        $action->update($model);

        $model->refresh();
        $this->assertEquals(1, $model->fail_count);
        $this->assertInstanceOf(Carbon::class, $model->last_failed);
    }
}
