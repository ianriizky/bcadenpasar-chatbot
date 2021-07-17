<?php

namespace App\Models\Concerns\Item;

/**
 * @property int $quantity_per_bundle
 * @property int $bundle_quantity
 * @property-read int $quantity
 * @property-read string $denomination_name
 * @property-read float $denomination_value
 * @property-read float $total
 *
 * @see \App\Models\Item
 */
trait Attribute
{
    /**
     * Return "quantity" attribute value.
     *
     * @return int
     */
    public function getQuantityAttribute(): int
    {
        return $this->quantity_per_bundle * $this->bundle_quantity;
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
}
