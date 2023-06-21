<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use JustBetter\MagentoCustomerPrices\Actions\UpdateCustomerPrices;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class UpdateCustomerPricesTest extends TestCase
{
    public UpdateCustomerPrices $action;

    public MagentoCustomerPrice $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(UpdateCustomerPrices::class);
        $this->model = MagentoCustomerPrice::create([
            'sku' => '::sku::',
            'prices' => [
                [
                    'sku' => '13901952', 'price' => '40.52000000', 'storeId' => 0, 'quantity' => 1, 'customerId' => 1,
                ],
            ],
        ]);
    }

    public function test_it_updates_price(): void
    {
        Http::fake([
            '*customer-pricing*' => Http::response([
                'firstname' => '::firstname::',
                'lastname' => '::lastname::',
            ]),
            '*products/::sku::' => Http::response(),
        ]);

        $this->action->update($this->model);

        Http::assertSent(function (Request $request) {
            return $request->data() === [
                'customerPrices' => [
                    [
                        'customer_id' => 1,
                        'quantity' => 1,
                        'price' => 40.52,
                    ],
                ],
            ];
        });
    }

    public function test_it_throws_error_on_fail(): void
    {
        Http::fake([
            '*customer-pricing*' => Http::response('::error::', 500),
        ]);

        $this->expectException(RequestException::class);

        $this->action->update($this->model);
    }
}
