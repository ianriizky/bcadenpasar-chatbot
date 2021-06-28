<?php

namespace App\Models\Concerns\OrderStatus;

use App\Models\Contracts\Issuerable;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $order_id Foreign key of \App\Models\Order.
 * @property-read \App\Models\Order $order
 * @property string $issuerable_type
 * @property int $issuerable_id
 * @property-read \App\Models\Contracts\Issuerable $issuerable
 *
 * @see \App\Models\OrderStatus
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
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     *
     * @see \App\Models\Customer
     * @see \App\Models\User
     */
    public function issuerable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Return \App\Models\Issuerable model relation value.
     *
     * @return \App\Models\Contracts\Issuerable
     */
    public function getIssuerableRelationValue(): Issuerable
    {
        return $this->getRelationValue('issuerable');
    }

    /**
     * Set \App\Models\Issuerable model relation value.
     *
     * @param  \App\Models\Contracts\Issuerable  $issuerable
     * @return $this
     */
    public function setIssuerableRelationValue(Issuerable $issuerable)
    {
        $this->issuerable()->associate($issuerable);

        return $this;
    }
}
