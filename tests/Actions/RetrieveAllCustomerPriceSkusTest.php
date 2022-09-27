<?php

namespace JustBetter\MagentoCustomerPrices\Tests\Actions;

use JustBetter\MagentoCustomerPrices\Actions\RetrieveAllCustomerPriceSkus;
use JustBetter\MagentoCustomerPrices\Retriever\DummyCustomerPriceRetriever;
use JustBetter\MagentoCustomerPrices\Tests\TestCase;
use Mockery\MockInterface;

class RetrieveAllCustomerPriceSkusTest extends TestCase
{
    public function test_it_calls_retriever(): void
    {
        config()->set('laravel-magento-customer-prices.retriever', DummyCustomerPriceRetriever::class);

        $this->mock(DummyCustomerPriceRetriever::class, function (MockInterface $mock) {
            $mock->shouldReceive('retrieveAllSkus')->andReturn(collect(['::sku::']));
        });

        /** @var RetrieveAllCustomerPriceSkus $action */
        $action = app(RetrieveAllCustomerPriceSkus::class);

        $skus = $action->retrieve();
        $this->assertEquals(['::sku::'], $skus->toArray());
    }
}
