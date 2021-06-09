<?php

namespace App\Models;

use App\Infrastructure\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class User extends Authenticatable
{
    use HasFactory, Notifiable,
        Concerns\User\Attribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone' => E164PhoneNumberCast::class,
        'email_verified_at' => 'datetime',
    ];
}
