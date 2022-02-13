<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User|null  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(?User $user, $ability)
    {
        if (in_array($ability, ['create', 'update', 'delete'])) {
            return;
        }

        if ($user && $user->hasRole([Role::ROLE_ADMIN, ROLE::ROLE_STAFF])) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(?User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User|null  $user
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Item $item)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $order = request()->route('order');

        return
            $user && $user->hasRole(Role::ROLE_ADMIN) &&
            !is_null($order) && !$order->latestStatus->hasBeenFinished() && !$order->latestStatus->hasBeenCanceled();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User|null  $user
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(?User $user, Item $item)
    {
        return
            $user && $user->hasRole(Role::ROLE_ADMIN) &&
            !$item->order->latestStatus->hasBeenFinished() && !$item->order->latestStatus->hasBeenCanceled();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User|null  $user
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(?User $user, Item $item)
    {
        return
            $user && $user->hasRole(Role::ROLE_ADMIN) &&
            !$item->order->latestStatus->hasBeenFinished() && !$item->order->latestStatus->hasBeenCanceled();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User|null  $user
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(?User $user, Item $item)
    {
        return $user && $user->hasRole(Role::ROLE_ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User|null  $user
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(?User $user, Item $item)
    {
        return
            $user && $user->hasRole(Role::ROLE_ADMIN) &&
            !$item->order->latestStatus->hasBeenFinished() && !$item->order->latestStatus->hasBeenCanceled();
    }
}
