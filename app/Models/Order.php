<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory,
        Concerns\Order\Attribute,
        Concerns\Order\Event,
        Concerns\Order\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'schedule_date',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'schedule_date' => 'datetime',
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'status',
        'item_total_bundle_quantity',
        'item_total',
    ];

    /**
     * Generate code for this model.
     *
     * @param  string  $prefix
     * @param  \Illuminate\Support\Carbon|null  $date
     * @return string
     */
    public function generateCode(string $prefix = 'BCA', Carbon $date = null): string
    {
        $date ??= Carbon::today();

        return $prefix . '-' . $date->format('Ymd') . '-' . Str::random(5);
    }
}
