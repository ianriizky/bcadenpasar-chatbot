<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuration extends Model
{
    use HasFactory,
        Concerns\Configuration\Attribute;

    /**
     * Return configuration value of "maximum_total_order_value".
     *
     * @return float
     */
    public static function getMaximumTotalOrderValue(): float
    {
        return (float) static::where('key', 'maximum_total_order_value')->first('value')->value;
    }
}
