<?php

namespace App\Models\Concerns\Order;

use App\Enum\OrderStatus;

/**
 * @property string $code
 * @property \App\Enum\OrderStatus $status
 * @property \Illuminate\Support\Carbon $schedule_date
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
    public function getStatusAttribute(): OrderStatus
    {
        return $this->latestStatus->status;
    }
}
