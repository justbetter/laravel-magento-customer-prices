<?php

namespace JustBetter\MagentoCustomerPrices\Data;

class CustomerPriceData extends Data
{
    public array $rules = [
        'sku' => ['required'],
        'prices' => ['array'],
        'prices.*.price' => ['required', 'numeric'],
        'prices.*.customer_id' => ['required', 'integer'],
        'prices.*.quantity' => ['required', 'numeric'],
    ];

    public function checksum(): string
    {
        $json = json_encode($this->validated());

        throw_if($json === false, 'Failed to generate checksum');

        return md5($json);
    }
}
