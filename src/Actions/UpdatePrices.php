<?php

namespace JustBetter\MagentoCustomerPrices\Actions;

use JustBetter\MagentoCustomerPrices\Contracts\UpdatesMagentoCustomerPrices;
use JustBetter\MagentoCustomerPrices\Contracts\UpdatesPrices;
use JustBetter\MagentoCustomerPrices\Models\MagentoCustomerPrice;
use JustBetter\MagentoProducts\Contracts\ChecksMagentoExistence;

class UpdatePrices implements UpdatesPrices
{
    public function __construct(
        protected UpdatesMagentoCustomerPrices $magentoCustomerPrices,
        protected ChecksMagentoExistence $checkMagentoExistence
    ) {
    }

    public function update(string $sku): void
    {
        $model = MagentoCustomerPrice::findBySku($sku);

        if ($model === null) {
            return;
        }

        if (! $this->checkMagentoExistence->exists($sku)) {
            activity()
                ->performedOn($model)
                ->log('Product does not exist in Magento');

            $model->update(['sync' => false, 'state' => MagentoCustomerPrice::STATE_IDLE]);

            return;
        }

        $model->setState(MagentoCustomerPrice::STATE_UPDATING);

        $this->magentoCustomerPrices->update($model);

        activity()
            ->performedOn($model)
            ->log('Updated price');

        $model->last_updated = now();
        $model->sync = true;
        $model->setState(MagentoCustomerPrice::STATE_IDLE);
    }

    public static function bind(): void
    {
        app()->singleton(UpdatesPrices::class, static::class);
    }
}
