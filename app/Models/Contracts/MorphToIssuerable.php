<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface MorphToIssuerable
{
    /**
     * Define a polymorphic, inverse one-to-one or many relationship with model that implements \App\Models\Contracts\Issuerable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function issuerable(): MorphTo;

    /**
     * Return \App\Models\Issuerable model relation value.
     *
     * @return \App\Models\Contracts\Issuerable
     */
    public function getIssuerableRelationValue(): Issuerable;

    /**
     * Set \App\Models\Issuerable model relation value.
     *
     * @param  \App\Models\Contracts\Issuerable  $issuerable
     * @return $this
     */
    public function setIssuerableRelationValue(Issuerable $issuerable);
}
