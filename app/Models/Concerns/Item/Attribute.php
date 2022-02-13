<?php

namespace App\Models\Concerns\Item;

/**
 * @property int $quantity_per_bundle
 * @property int|null $bundle_quantity
 * @property int $quantity
 * @property bool $is_order_custom_quantity
 * @property-read string $denomination_name
 * @property-read float $denomination_value
 * @property-read float $total
 * @property-read string $is_order_custom_quantity_badge
 *
 * @see \App\Models\Item
 */
trait Attribute
{
    /**
     * Return "quantity" attribute value.
     *
     * @param  mixed  $value
     * @return int
     */
    public function getQuantityAttribute($value): int
    {
        if ($this->is_order_custom_quantity) {
            return $this->castAttribute('quantity', $value);
        }

        return $this->countQuantityAttribute();
    }

    /**
     * Return "denomination_name" attribute value.
     *
     * @return string
     */
    public function getDenominationNameAttribute(): string
    {
        return $this->denomination->name;
    }

    /**
     * Return "denomination_value" attribute value.
     *
     * @return float
     */
    public function getDenominationValueAttribute(): float
    {
        return $this->denomination->value;
    }

    /**
     * Return "total" attribute value.
     *
     * @return float
     */
    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->denomination_value;
    }

    /**
     * Return "is_order_custom_quantity_badge" attribute value.
     *
     * @return string
     */
    public function getIsOrderCustomQuantityBadgeAttribute(): string
    {
        return sprintf(<<<'html'
            <span class="badge badge-%s">
                <i class="fa fa-%s"></i> %s
            </span>
        html,
            $this->is_order_custom_quantity ? 'success' : 'danger',
            $this->is_order_custom_quantity ? 'check-circle' : 'times-circle',
            $this->is_order_custom_quantity ? trans('Yes') : trans('No')
        );
    }
}
