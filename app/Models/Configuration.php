<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuration extends Model
{
    use HasFactory,
        Concerns\Configuration\Attribute;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Return configuration value of "maximum_total_order_value".
     *
     * @return float
     */
    public static function getMaximumTotalOrderValue(): float
    {
        return (float) static::where('key', 'maximum_total_order_value')->first('value')->value;
    }

    /**
     * Return configuration value of "maximum_order_per_day".
     *
     * @return int
     */
    public static function getMaximumOrderPerDay(): int
    {
        return (int) static::where('key', 'maximum_order_per_day')->first('value')->value;
    }
}
