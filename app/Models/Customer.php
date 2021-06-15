<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Customer extends Model
{
    use HasFactory,
        Concerns\Customer\Attribute;

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
        'whatsapp_phone_country',
        'whatsapp_phone',
        'accountnumber',
        'identitycardnumber',
        'identitycardimage',
        'location_latitude',
        'location_longitude',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'phone' => E164PhoneNumberCast::class,
        'whatsapp_phone' => E164PhoneNumberCast::class,
        'location_latitude' => 'float',
        'location_longitude' => 'float',
    ];
}
