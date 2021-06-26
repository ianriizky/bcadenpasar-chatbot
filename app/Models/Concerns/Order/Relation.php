<?php

namespace App\Models\Concerns\Order;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Item;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $customer_id Foreign key of \App\Models\Customer.
 * @property-read \App\Models\Customer $customer
 * @property int|null $user_id Foreign key of \App\Models\User.
 * @property-read \App\Models\User|null $user
 * @property int|null $branch_id Foreign key of \App\Models\Branch.
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\OrderStatus> $statuses
 * @property-read \App\Models\OrderStatus $latestStatus
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Item> $items
 *
 * @see \App\Models\Order
 */
trait Relation
{
    /**
     * Define an inverse one-to-one or many relationship with \App\Models\Customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Return \App\Models\Customer model relation value.
     *
     * @return \App\Models\Customer
     */
    public function getCustomerRelationValue(): Customer
    {
        return $this->getRelationValue('customer');
    }

    /**
     * Set \App\Models\Customer model relation value.
     *
     * @param  \App\Models\Customer  $customer
     * @return $this
     */
    public function setCustomerRelationValue(Customer $customer)
    {
        $this->customer()->associate($customer);

        return $this;
    }

    /**
     * Define an inverse one-to-one or many relationship with \App\Models\User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return \App\Models\User model relation value.
     *
     * @return \App\Models\User|null
     */
    public function getUserRelationValue(): ?User
    {
        return $this->getRelationValue('user');
    }

    /**
     * Set \App\Models\User model relation value.
     *
     * @param  \App\Models\User  $user
     * @return $this
     */
    public function setUserRelationValue(User $user)
    {
        $this->user()->associate($user);

        return $this;
    }

    /**
     * Define an inverse one-to-one or many relationship with \App\Models\Branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Return \App\Models\Branch model relation value.
     *
     * @return \App\Models\Branch
     */
    public function getBranchRelationValue(): Branch
    {
        return $this->getRelationValue('branch');
    }

    /**
     * Set \App\Models\Branch model relation value.
     *
     * @param  \App\Models\Branch  $branch
     * @return $this
     */
    public function setBranchRelationValue(Branch $branch)
    {
        $this->branch()->associate($branch);

        return $this;
    }

    /**
     * Define a one-to-many relationship with App\Models\OrderStatus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class);
    }

    /**
     * Return collection of \App\Models\OrderStatus model relation value.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\OrderStatus>
     */
    public function getStatusesRelationValue(): Collection
    {
        return $this->getCollectionValue('statuses', OrderStatus::class);
    }

    /**
     * Set collection of \App\Models\OrderStatus model relation value.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\OrderStatus>  $statuses
     * @return $this
     */
    public function setStatusesRelationValue(Collection $statuses)
    {
        if ($this->isCollectionValid($statuses, OrderStatus::class)) {
            $this->setRelation('statuses', $statuses);
        }

        return $this;
    }

    /**
     * Define a one-to-one relationship with \App\Models\OrderStatus from a larger one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestStatus(): HasOne
    {
        return $this->hasOne(OrderStatus::class)->latestOfMany('created_at');
    }

    /**
     * Return \App\Models\OrderStatus model relation value.
     *
     * @return \App\Models\OrderStatus
     */
    public function getLatestStatusRelationValue(): ?OrderStatus
    {
        return $this->getRelationValue('latestStatus');
    }

    /**
     * Define a one-to-many relationship with App\Models\Item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Return collection of \App\Models\Item model relation value.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Item>
     */
    public function getItemsRelationValue(): Collection
    {
        return $this->getCollectionValue('items', Item::class);
    }

    /**
     * Set collection of \App\Models\Item model relation value.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\Item>  $items
     * @return $this
     */
    public function setItemsRelationValue(Collection $items)
    {
        if ($this->isCollectionValid($items, Item::class)) {
            $this->setRelation('items', $items);
        }

        return $this;
    }
}
