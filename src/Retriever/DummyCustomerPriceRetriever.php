<?php

namespace JustBetter\MagentoCustomerPrices\Retriever;

use Illuminate\Support\Collection;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use JustBetter\MagentoCustomerPrices\Helpers\MoneyHelper;

class DummyCustomerPriceRetriever extends CustomerPriceRetriever
{
    public function __construct(protected MoneyHelper $moneyHelper)
    {
    }

    /** @return Collection<CustomerPriceData> */
    public function retrieve(string $sku): Collection
    {
        return collect([
            new CustomerPriceData($sku, $this->moneyHelper->getMoney(10), 1),
        ]);
    }

    public function retrieveAllSkus(): Collection
    {
        return collect(['sku']);
    }

    public function retrieveUpdatedSkus(): Collection
    {
        return collect(['sku']);
    }
}
