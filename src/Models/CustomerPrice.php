<?php

namespace JustBetter\MagentoCustomerPrices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use JustBetter\MagentoCustomerPrices\Repository\BaseRepository;
use JustBetter\MagentoProducts\Models\MagentoProduct;
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
 * @property ?MagentoProduct $product
 */
class CustomerPrice extends Model
{
    use LogsActivity;

    protected $table = 'magento_customer_prices';

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

    public static function booted(): void
    {
        static::updating(function (self $model) {
            if ($model->update && $model->retrieve) {
                if (! $model->isDirty(['retrieve'])) {
                    $model->retrieve = false;
                } else {
                    $model->update = false;
                }
            }
        });
    }

    public function product(): HasOne
    {
        return $this->hasOne(MagentoProduct::class, 'sku', 'sku');
    }

    public function registerFailure(): void
    {
        $this->fail_count++;
        $this->last_failed = now();

        if ($this->fail_count > BaseRepository::resolve()->failLimit()) {
            $this->update = false;
            $this->retrieve = false;
            $this->fail_count = 0;
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
