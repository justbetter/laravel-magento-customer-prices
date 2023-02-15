<?php

namespace JustBetter\MagentoCustomerPrices\Data;

use Brick\Money\Money;
use Illuminate\Contracts\Support\Arrayable;
use JustBetter\MagentoCustomerPrices\Helpers\MoneyHelper;

/** @phpstan-consistent-constructor */
class CustomerPriceData implements Arrayable
{
    public function __construct(
        public string $sku,
        public Money $price,
        public int $customerId,
        public int $quantity = 0,
        public int $storeId = 0
    ) {
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function setStoreId(int $storeId): void
    {
        $this->storeId = $storeId;
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->getSku(),
            'price' => (string) $this->getPrice()->getAmount(),
            'customerId' => $this->getCustomerId(),
            'quantity' => $this->getQuantity(),
            'storeId' => $this->getStoreId(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['sku'],
            app(MoneyHelper::class)->getMoney($data['price']),
            $data['customerId'],
            $data['quantity'] ?? 0,
            $data['storeId'] ?? 0
        );
    }

    public function toMageplazaData(): array
    {
        return [
            'website_id' => (string) $this->storeId,
            'customer_id' => (string) $this->customerId,
            'price_qty' =>(string) max($this->quantity, 1),
            'value_type' => 'fixed',
            'price' => (string) $this->getPrice()->getAmount()->toFloat(),
            'initialize' => '1',
        ];
    }

    public function equals(self $other): bool
    {
        /** @var string $a */
        $a = json_encode($this->toArray());

        /** @var string $b */
        $b = json_encode($other->toArray());

        return md5($a) == md5($b);
    }
}
