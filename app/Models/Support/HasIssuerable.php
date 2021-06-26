<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\OrderStatus> $issuers
 */
trait HasIssuerable
{
    /**
     * Define a polymorphic one-to-many relationship with \App\Models\OrderStatus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function issuers(): MorphMany
    {
        return $this->morphMany(OrderStatus::class, 'issuerable');
    }
}
