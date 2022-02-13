<?php

namespace App\Models\Support\Relation;

use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\OrderStatus> $issuerOrderStatuses
 */
trait MorphManyIssueOrderStatus
{
    /**
     * Define a polymorphic one-to-many relationship with \App\Models\OrderStatus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function issuerOrderStatuses(): MorphMany
    {
        return $this->morphMany(OrderStatus::class, 'issuerable');
    }
}
