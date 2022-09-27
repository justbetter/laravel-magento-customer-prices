<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Retriever;

use JustBetter\MagentoCustomerPrices\Retriever\DummyCustomerPriceRetriever;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;

class DummyCustomerPriceRetrieverTest extends TestCase
{
    public function test_it_retrieves_dummy_data(): void
    {
        /** @var DummyCustomerPriceRetriever $retriever */
        $retriever = app(DummyCustomerPriceRetriever::class);

        $this->assertEquals([
            [
                'sku' => '::sku::',
                'price' => '10.0000',
                'customerId' => 1,
                'quantity' => 0,
                'storeId' => 0,
            ],
        ], $retriever->retrieve('::sku::')->toArray());

        $this->assertEquals(['sku'], $retriever->retrieveUpdatedSkus()->toArray());
        $this->assertEquals(['sku'], $retriever->retrieveAllSkus()->toArray());
    }
}
