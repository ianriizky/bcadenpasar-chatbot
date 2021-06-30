<?php

namespace Tests\Feature\Concerns;

use App\Models\Branch;
use App\Models\User;

trait HandleAuthentication
{
    /**
     * Generate user data with its relationship.
     *
     * @return \App\Models\User
     */
    protected function createUserFromFactory(): User
    {
        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create();

        return $user;
    }
}
