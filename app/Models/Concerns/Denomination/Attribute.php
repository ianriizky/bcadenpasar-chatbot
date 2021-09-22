<?php

namespace App\Models\Concerns\Denomination;

use Illuminate\Support\Facades\Storage;

/**
 * @property string $key
 * @property string $name
 * @property float $value
 * @property \App\Enum\DenominationType $type
 * @property int $quantity_per_bundle
 * @property int $minimum_order_bundle
 * @property int $maximum_order_bundle
 * @property int $minimum_order_quantity
 * @property int $maximum_order_quantity
 * @property bool $can_order_custom_quantity
 * @property bool $is_visible
 * @property string $image
 * @property-read array $range_order_bundle
 * @property-read float $value_per_bundle
 * @property-read float $minimum_order_value
 * @property-read float $maximum_order_value
 * @property-read string $value_rupiah
 * @property-read string $type_badge
 * @property-read string $can_order_custom_quantity_badge
 * @property-read string $is_visible_badge
 *
 * @see \App\Models\Denomination
 */
trait Attribute
{
    /**
     * Return "minimum_order_quantity" attribute value.
     *
     * @param  mixed  $value
     * @return int
     */
    public function getMinimumOrderQuantityAttribute($value): int
    {
        if ($this->can_order_custom_quantity) {
            return $this->castAttribute('minimum_order_quantity', $value);
        }

        return $this->countMinimumOrderQuantityAttribute();
    }

    /**
     * Return "maximum_order_quantity" attribute value.
     *
     * @param  mixed  $value
     * @return int
     */
    public function getMaximumOrderQuantityAttribute($value): int
    {
        if ($this->can_order_custom_quantity) {
            return $this->castAttribute('maximum_order_quantity', $value);
        }

        return $this->countMaximumOrderQuantityAttribute();
    }

    /**
     * Return "image" attribute value.
     *
     * @param  mixed  $value
     * @return string
     */
    public function getImageAttribute($value): string
    {
        if (is_null($value)) {
            return asset('img/dummy.png');
        }

        return Storage::url(static::IMAGE_PATH . '/' . $value);
    }

    /**
     * Return "range_order_bundle" attribute value.
     *
     * @return array
     */
    public function getRangeOrderBundleAttribute(): array
    {
        return range($this->minimum_order_bundle, $this->maximum_order_bundle);
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

    /**
     * Return "value_rupiah" attribute value.
     *
     * @return string
     */
    public function getValueRupiahAttribute(): string
    {
        return format_rupiah($this->value);
    }

    /**
     * Return "type_badge" attribute value.
     *
     * @return string
     */
    public function getTypeBadgeAttribute(): string
    {
        return sprintf(<<<'html'
            <span class="badge badge-%s">
                <i class="fa fa-%s"></i> %s
            </span>
        html,
            $this->type->isCoin() ? 'danger' : 'success',
            $this->type->isCoin() ? 'coins' : 'money-bill',
            $this->type->label
        );
    }

    /**
     * Return "can_order_custom_quantity_badge" attribute value.
     *
     * @return string
     */
    public function getCanOrderCustomQuantityBadgeAttribute(): string
    {
        return sprintf(<<<'html'
            <span class="badge badge-%s">
                <i class="fa fa-%s"></i> %s
            </span>
        html,
            $this->can_order_custom_quantity ? 'success' : 'danger',
            $this->can_order_custom_quantity ? 'check-circle' : 'times-circle',
            $this->can_order_custom_quantity ? trans('Yes') : trans('No')
        );
    }

    /**
     * Return "is_visible_badge" attribute value.
     *
     * @return string
     */
    public function getIsVisibleBadgeAttribute(): string
    {
        return sprintf(<<<'html'
            <span class="badge badge-%s">
                <i class="fa fa-%s"></i> %s
            </span>
        html,
            $this->is_visible ? 'success' : 'danger',
            $this->is_visible ? 'check-circle' : 'times-circle',
            $this->is_visible ? trans('Yes') : trans('No')
        );
    }
}
