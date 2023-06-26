<?php

namespace JustBetter\MagentoCustomerPrices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JustBetter\MagentoCustomerPrices\Data\CustomerPriceData;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $sku
 * @property bool $sync
 * @property array $prices
 * @property string $state
 * @property ?Carbon $last_retrieved
 * @property ?Carbon $last_updated
 * @property int $fail_count
 * @property ?Carbon $last_failed
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class MagentoCustomerPrice extends Model
{
    use LogsActivity;

    public const STATE_IDLE = 'idle';

    public const STATE_RETRIEVE = 'retrieve';

    public const STATE_RETRIEVING = 'retrieving';

    public const STATE_UPDATE = 'update';

    public const STATE_UPDATING = 'updating';

    public const STATE_FAILED = 'failed';

    public $guarded = [];

    public $casts = [
        'sync' => 'boolean',
        'prices' => 'array',
        'last_retrieved' => 'datetime',
        'last_updated' => 'datetime',
        'last_failed' => 'datetime',
    ];

    public function setState(string $state): static
    {
        $this->update(['state' => $state]);

        return $this;
    }

    public function getDataCollection(): Collection
    {
        return collect($this->prices)
            ->map(fn (array $price) => CustomerPriceData::fromArray($price));
    }

    public static function findBySku(string $sku): ?static
    {
        /** @var ?static $item */
        $item = static::query()
            ->where('sku', $sku)
            ->first();

        return $item;
    }

    public function registerFail(string $previousState): void
    {
        $this->last_failed = now();
        $this->fail_count++;

        if ($this->fail_count > config('magento-customer-prices.fail_count', 5)) {
            $this->setState(MagentoCustomerPrice::STATE_FAILED);
            $this->sync = false;
        } else {
            $this->setState($previousState);
        }

        $this->save();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logOnly(['sync', 'prices']);
    }
}
