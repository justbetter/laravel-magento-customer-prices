<?php

namespace JustBetter\MagentoCustomerPrices\Actions\Update;

use Illuminate\Http\Client\Response;
use JustBetter\MagentoClient\Client\Magento;
use JustBetter\MagentoCustomerPrices\Contracts\Update\UpdatesCustomerPriceSync;
use JustBetter\MagentoCustomerPrices\Models\CustomerPrice;
use JustBetter\MagentoProducts\Contracts\ChecksMagentoExistence;

class UpdateCustomerPriceSync implements UpdatesCustomerPriceSync
{
    public function __construct(
        protected Magento $magento,
        protected ChecksMagentoExistence $magentoExistence
    ) {
    }

    public function update(CustomerPrice $price): void
    {
        if (!$this->magentoExistence->exists($price->sku)) {
            $price->update([
                'update' => false,
            ]);

            return;
        }

        $this->magento->post('customer-pricing/'.urlencode($price->sku), [
            'customerPrices' => $price->prices,
        ])->onError(function (Response $response) use ($price): void {
            activity()
                ->on($price)
                ->useLog('error')
                ->withProperties([
                    'response' => $response->body(),
                ])
                ->log('Failed to update customer price');
        })->throw();

        $price->update([
            'update' => false,
        ]);
    }

    public static function bind(): void
    {
        app()->singleton(UpdatesCustomerPriceSync::class, static::class);
    }
}
