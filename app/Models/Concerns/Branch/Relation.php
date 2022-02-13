<?php

namespace App\Models\Concerns\Branch;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\User> $users
 *
 * @see \App\Models\Branch
 */
trait Relation
{
    /**
     * Define a one-to-many relationship with App\Models\User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Return collection of \App\Models\User model relation value.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public function getUsersRelationValue(): Collection
    {
        return $this->getCollectionValue('users', User::class);
    }

    /**
     * Set collection of \App\Models\User model relation value.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\User>  $users
     * @return $this
     */
    public function setUsersRelationValue(Collection $users)
    {
        if ($this->isCollectionValid($users, User::class)) {
            $this->setRelation('users', $users);
        }

        return $this;
    }
}
