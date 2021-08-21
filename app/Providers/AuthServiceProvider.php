<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\User;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $policies = [
        OrderStatus::class => OrderPolicy::class,
        Item::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-dashboard', function (User $user) {
            return $user->hasRole([Role::ROLE_ADMIN, Role::ROLE_STAFF]);
        });
    }
}
