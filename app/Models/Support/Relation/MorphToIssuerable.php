<?php

namespace App\Models\Support\Relation;

use App\Models\Contracts\Issuerable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $issuerable_type
 * @property int $issuerable_id
 * @property-read \App\Models\Contracts\Issuerable $issuerable
 */
trait MorphToIssuerable
{
    /**
     * Define a polymorphic, inverse one-to-one or many relationship with model that implements \App\Models\Contracts\Issuerable.
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
