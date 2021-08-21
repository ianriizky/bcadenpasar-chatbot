<?php

namespace App\Models\Concerns\Order;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Models\OrderStatus as ModelsOrderStatus;

/**
 * @property string $code
 * @property \App\Enum\OrderStatus $status
 * @property \Illuminate\Support\Carbon $schedule_date
 * @property-read int $item_total_bundle_quantity
 * @property-read float $item_total
 *
 * @see \App\Models\Order
 */
trait Attribute
{
    /**
     * Return "status" attribute value.
     *
     * @return \App\Enum\OrderStatus
     */
    public function getStatusAttribute(): EnumOrderStatus
    {
        return ($this->latestStatus ?? new ModelsOrderStatus([
            'status' => EnumOrderStatus::draft(),
        ]))->status;
    }

    /**
     * Return "item_total_bundle_quantity" attribute value.
     *
     * @return int
     */
    public function getItemTotalBundleQuantityAttribute(): int
    {
        return $this->items->sum->bundle_quantity;
    }

    /**
     * Return "item_total" attribute value.
     *
     * @return float
     */
    public function getItemTotalAttribute(): float
    {
        return $this->items->sum->total;
    }
}
