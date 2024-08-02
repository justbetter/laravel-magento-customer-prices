<?php

namespace JustBetter\MagentoCustomerPrices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $sku
 * @property bool $sync
 * @property bool $retrieve
 * @property bool $update
 * @property array $prices
 * @property ?Carbon $last_retrieved
 * @property ?Carbon $last_updated
 * @property int $fail_count
 * @property ?Carbon $last_failed
 * @property ?string $checksum
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class CustomerPrice extends Model
{
    use LogsActivity;

    public $guarded = [];

    public $casts = [
        'sync' => 'boolean',
        'retrieve' => 'boolean',
        'update' => 'boolean',
        'prices' => 'array',
        'last_retrieved' => 'datetime',
        'last_updated' => 'datetime',
        'last_failed' => 'datetime',
    ];

    public function registerFailure(string $previousState): void
    {
        $this->last_failed = now();
        $this->fail_count++;

        if ($this->fail_count > config('magento-customer-prices.fail_count', 5)) {
            $this->update = false;
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
