<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Issuerable
{
    /**
     * Define a polymorphic one-to-many relationship with \App\Models\OrderStatus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function issuers(): MorphMany;
}
