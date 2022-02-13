<?php

namespace App\Models\Concerns\Item;

use App\Models\Denomination;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|static whereHasDenomination(\App\Models\Denomination $denomination)
 *
 * @see \App\Models\Item
 * @see \Illuminate\Database\Eloquent\Builder @callScope()
 */
trait QueryScope
{
    /**
     * Scope a query to find where has specified denomination.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereHasDenomination(Builder $query, Denomination $denomination)
    {
        return $query->whereHas('denomination', function ($query) use ($denomination) {
            $query->whereKey($denomination->getKey());
        });
    }
}
