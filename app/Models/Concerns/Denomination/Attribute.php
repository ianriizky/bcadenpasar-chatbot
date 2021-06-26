<?php

namespace App\Models\Concerns\Denomination;

use Illuminate\Support\Facades\Storage;

/**
 * @property string $name
 * @property float $value
 * @property \App\Enum\DenominationType $type
 * @property int $quantity_per_bundle
 * @property int $minimum_order_bundle
 * @property int $maximum_order_bundle
 * @property string|null $image
 * @property-read float $value_per_bundle
 * @property-read float $minimum_order_value
 * @property-read float $maximum_order_value
 *
 * @see \App\Models\Denomination
 */
trait Attribute
{
    /**
     * Return "image" attribute value.
     *
     * @param  mixed  $value
     * @return string|null
     */
    public function getImageAttribute($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return Storage::url(static::IMAGE_PATH . '/' . $value);
    }

    /**
     * Return "value_per_bundle" attribute value.
     *
     * @return float
     */
    public function getValuePerBundleAttribute(): float
    {
        return $this->value * $this->quantity_per_bundle;
    }

    /**
     * Return "minimum_order_value" attribute value.
     *
     * @return float
     */
    public function getMinimumOrderValueAttribute(): float
    {
        return $this->value_per_bundle * $this->minimum_order_bundle;
    }

    /**
     * Return "maximum_order_value" attribute value.
     *
     * @return float
     */
    public function getMaximumOrderValueAttribute(): float
    {
        return $this->value_per_bundle * $this->maximum_order_bundle;
    }
}
