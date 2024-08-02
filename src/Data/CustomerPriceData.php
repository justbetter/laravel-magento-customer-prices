<?php

namespace JustBetter\MagentoCustomerPrices\Data;

use JustBetter\MagentoPrices\Data\Data;

class CustomerPriceData extends Data
{
    public array $rules = [
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
