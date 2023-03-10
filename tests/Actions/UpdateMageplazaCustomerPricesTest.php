<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use JustBetter\MagentoCustomerPrices\Actions\UpdateMageplazaCustomerPrices;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class UpdateMageplazaCustomerPricesTest extends TestCase
{
    public UpdateMageplazaCustomerPrices $action;

    public MagentoCustomerPrice $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(UpdateMageplazaCustomerPrices::class);
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
            '*customers/1' => Http::response([
                'firstname' => '::firstname::',
                'lastname' => '::lastname::',
            ]),
            '*products/::sku::' => Http::response(),
        ]);

        $this->action->update($this->model);

        Http::assertSentInOrder([
            function (Request $request) {
                return $request->url() == 'rest/all/V1/customers/1';
            },
            function (Request $request) {
                $expectedData = [
                    'product' => [
                        'custom_attributes' => [
                            [
                                'attribute_code' => 'mp_specific_customer',
                                'value' => '[{"website_id":"0","customer_id":"1","price_qty":"1","value_type":"fixed","price":"40.52","initialize":"1","customer":"::firstname:: ::lastname::","record_id":"0"}]',
                            ],
                        ],
                    ],
                ];

                return $request->data() == $expectedData;
            },
        ]);
    }

    public function test_it_throws_error_on_fail(): void
    {
        Http::fake([
            '*customers/1' => Http::response([
                'firstname' => '::firstname::',
                'lastname' => '::lastname::',
            ]),
            '*products/::sku::' => Http::response('::error::', 500),
        ]);

        $this->expectException(RequestException::class);

        $this->action->update($this->model);
    }
}
