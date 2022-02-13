<?php

namespace App\Models\Support\Relation;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Customer> $issuerCustomers
 */
trait MorphManyIssueCustomers
{
    /**
     * Define a polymorphic one-to-many relationship with \App\Models\Customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function issuerCustomers(): MorphMany
    {
        return $this->morphMany(Customer::class, 'issuerable');
    }
}
