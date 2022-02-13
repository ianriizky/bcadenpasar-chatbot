<?php

namespace App\Models\Concerns\OrderStatus;

use App\Models\Order;
use App\Models\Support\Relation\MorphToIssuerable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $order_id Foreign key of \App\Models\Order.
 * @property-read \App\Models\Order $order
 *
 * @see \App\Models\OrderStatus
 */
trait Relation
{
    use MorphToIssuerable;

    /**
     * Define an inverse one-to-one or many relationship with \App\Models\Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Return \App\Models\Order model relation value.
     *
     * @return \App\Models\Order
     */
    public function getOrderRelationValue(): Order
    {
        return $this->getRelationValue('order');
    }

    /**
     * Set \App\Models\Order model relation value.
     *
     * @param  \App\Models\Order  $order
     * @return $this
     */
    public function setOrderRelationValue(Order $order)
    {
        $this->order()->associate($order);

        return $this;
    }
}
