<?php

namespace App\Models\Concerns\Item;

use App\Models\Denomination;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $order_id Foreign key of \App\Models\Order.
 * @property-read \App\Models\Order $order
 * @property int $denomination_id Foreign key of \App\Models\Denomination.
 * @property-read \App\Models\Denomination $denomination
 *
 * @see \App\Models\Item
 */
trait Relation
{
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

    /**
     * Define an inverse one-to-one or many relationship with \App\Models\Denomination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function denomination(): BelongsTo
    {
        return $this->belongsTo(Denomination::class);
    }

    /**
     * Return \App\Models\Denomination model relation value.
     *
     * @return \App\Models\Denomination
     */
    public function getDenominationRelationValue(): Denomination
    {
        return $this->getRelationValue('denomination');
    }

    /**
     * Set \App\Models\Denomination model relation value.
     *
     * @param  \App\Models\Denomination  $denomination
     * @return $this
     */
    public function setDenominationRelationValue(Denomination $denomination)
    {
        $this->denomination()->associate($denomination);

        return $this;
    }
}
