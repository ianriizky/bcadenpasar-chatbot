<?php

namespace App\Models\Concerns\Denomination;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|static whereIsVisible(bool $visible = true)
 *
 * @see \App\Models\Denomination
 * @see \Illuminate\Database\Eloquent\Builder @callScope()
 */
trait QueryScope
{
    /**
     * Scope a query to find where has specified denomination.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $visible
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereIsVisible(Builder $query, bool $visible = true)
    {
        return $query->where('is_visible', $visible);
    }
}
