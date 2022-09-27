<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use Illuminate\Support\Enumerable;
use JustBetter\MagentoCustomerPrices\Contracts\DeterminesPricesModified;
use JustBetter\MagentoCustomerPrices\Contracts\ProcessesRetrievedPrice;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;

class ProcessRetrievedPrice implements ProcessesRetrievedPrice
{
    public function __construct(
        protected DeterminesPricesModified $determinePricesModified
    ) {
    }

    public function process(string $sku, Enumerable $priceData, bool $forceUpdate = false): void
    {
        $model = MagentoCustomerPrice::findBySku($sku);

        if ($model === null) {
            if ($priceData->isEmpty()) {
                return;
            }

            $model = MagentoCustomerPrice::create(['sku' => $sku]);
        }

        activity()
            ->performedOn($model)
            ->withProperties($priceData->toArray())
            ->log('Retrieved price');

        $model->last_retrieved = now();

        if (
            $forceUpdate
            || $model->last_updated == null
            || $this->determinePricesModified->check($priceData, $model->getDataCollection())
        ) {
            $model->prices = $priceData->toArray();
            $model->setState(MagentoCustomerPrice::STATE_UPDATE);
        } else {
            $model->setState(MagentoCustomerPrice::STATE_IDLE);
        }
    }

    public static function bind(): void
    {
        app()->singleton(ProcessesRetrievedPrice::class, static::class);
    }
}
