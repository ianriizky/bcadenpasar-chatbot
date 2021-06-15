<?php

namespace App\Models;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordQueued;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,
        Concerns\User\Attribute,
        Concerns\User\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'username',
        'fullname',
        'gender',
        'email',
        'phone_country',
        'phone',
        'password',
    ];

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'gender' => Gender::class,
        'phone' => E164PhoneNumberCast::class,
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * {@inheritDoc}
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
    }

    /**
     * {@inheritDoc}
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordQueued($token));
    }
}
