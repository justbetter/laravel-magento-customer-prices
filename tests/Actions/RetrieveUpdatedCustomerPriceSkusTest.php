<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use JustBetter\MagentoCustomerPrices\Actions\RetrieveUpdatedPriceSkus;
use JustBetter\MagentoCustomerPrices\Retriever\DummyCustomerPriceRetriever;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class RetrieveUpdatedCustomerPriceSkusTest extends TestCase
{
    public function test_it_calls_retriever(): void
    {
        config()->set('laravel-magento-customer-prices.retriever', DummyCustomerPriceRetriever::class);

        $this->mock(DummyCustomerPriceRetriever::class, function (MockInterface $mock) {
            $mock->shouldReceive('retrieveUpdatedSkus')->andReturn(collect(['::sku::']));
        });

        /** @var RetrieveUpdatedPriceSkus $action */
        $action = app(RetrieveUpdatedPriceSkus::class);

        $skus = $action->retrieve();
        $this->assertEquals(['::sku::'], $skus->toArray());
    }
}
