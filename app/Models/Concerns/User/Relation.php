<?php

namespace App\Models\Concerns\User;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Role> $roles
 * @property int $branch_id Foreign key of \App\Models\Branch.
 * @property-read \App\Models\Branch $branch
 *
 * @see \App\Models\User
 */
trait Relation
{
    use HasRoles;

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
}
