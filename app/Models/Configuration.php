<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $key
 * @property string|null $value
 * @property string|null $description
 */
class Configuration extends Model
{
    use HasFactory;

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
     * @return float|null
     */
    public static function getMaximumTotalOrderValue(): ?float
    {
        return static::where('key', 'maximum_total_order_value')->value('value');
    }

    /**
     * Return configuration value of "maximum_order_per_day".
     *
     * @return int
     */
    public static function getMaximumOrderPerDay(): int
    {
        return static::where('key', 'maximum_order_per_day')->value('value');
    }
}
