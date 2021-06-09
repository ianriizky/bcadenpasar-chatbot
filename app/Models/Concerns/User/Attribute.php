<?php

namespace App\Models\Concerns\User;

use Illuminate\Support\Facades\Hash;

/**
 * @property string $username
 * @property string $fullname
 * @property string $email
 * @property string $phone_country
 * @property \Propaganistas\LaravelPhone\PhoneNumber|null $phone
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
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
}
