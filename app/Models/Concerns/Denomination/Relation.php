<?php

namespace App\Models\Concerns\Denomination;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @see \App\Models\Denomination
 */
trait Relation
{
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
