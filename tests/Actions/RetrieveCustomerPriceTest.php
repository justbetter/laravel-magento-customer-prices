<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use JustBetter\MagentoCustomerPrices\Actions\RetrieveCustomerPrice;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoCustomerPrices\Retriever\DummyCustomerPriceRetriever;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class RetrieveCustomerPriceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(DummyCustomerPriceRetriever::class, function (MockInterface $mock) {
            $mock->shouldReceive('retrieve')
                ->with('::sku::')
                ->andReturn(collect(['result']));
        });
    }

    public function test_it_sets_status_and_calls_retriever(): void
    {
        /** @var CustomerPrice $price */
        $price = CustomerPrice::create(['sku' => '::sku::']);

        /** @var RetrieveCustomerPrice $action */
        $action = app(RetrieveCustomerPrice::class);
        $result = $action->retrieve('::sku::')->toArray();

        $this->assertEquals(CustomerPrice::STATE_RETRIEVING, $price->refresh()->state);
        $this->assertEquals(['result'], $result);
    }
}
