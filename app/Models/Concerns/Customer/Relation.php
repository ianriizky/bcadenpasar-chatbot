<?php

namespace App\Models\Concerns\Customer;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Order> $orders
 *
 * @see \App\Models\Customer
 */
trait Relation
{
    /**
     * Define a one-to-many relationship with App\Models\Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Return collection of \App\Models\Order model relation value.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Order>
     */
    public function getOrdersRelationValue(): Collection
    {
        return $this->getCollectionValue('orders', Order::class);
    }

    /**
     * Set collection of \App\Models\Order model relation value.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\Order>  $orders
     * @return $this
     */
    public function setOrdersRelationValue(Collection $orders)
    {
        if ($this->isCollectionValid($orders, Order::class)) {
            $this->setRelation('orders', $orders);
        }

        return $this;
    }
}
