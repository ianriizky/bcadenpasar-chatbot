<?php

namespace App\Models\Concerns\User;

use Illuminate\Support\Facades\Hash;

/**
 * @property string|null $telegram_chat_id
 * @property string $username
 * @property string $fullname
 * @property string $email
 * @property string $phone_country
 * @property \Propaganistas\LaravelPhone\PhoneNumber $phone
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property boolean $is_active
 * @property string $is_active_badge
 * @property-read string $role
 * @property-read string $gravatar_image
 *
 * @see \App\Models\User
 */
trait Attribute
{
    /**
     * Set "password" attribute value.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);

        return $this;
    }

    /**
     * Return "is_active_badge" attribute value.
     *
     * @return string
     */
    public function getIsActiveBadgeAttribute(): string
    {
        return sprintf(<<<'html'
            <span class="badge badge-%s">
                <i class="fa fa-%s"></i> %s
            </span>
        html,
            $this->is_active ? 'success' : 'danger',
            $this->is_active ? 'check-circle' : 'times-circle',
            $this->is_active ? trans('Active') : trans('Not Active')
        );
    }

    /**
     * Return "role" attribute value.
     *
     * @return string
     */
    public function getRoleAttribute(): string
    {
        $this->load('roles:id,name');

        return $this->roles->first()->name;
    }

    /**
     * Return "gravatar_image" attribute value.
     *
     * @return string
     */
    public function getGravatarImageAttribute(): string
    {
        return gravatar_image($this->email);
    }
}
