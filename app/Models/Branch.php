<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory,
        Concerns\Branch\Attribute,
        Concerns\Branch\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'address',
        'address_latitude',
        'address_longitude',
        'google_map_url',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'address_latitude' => 'float',
        'address_longitude' => 'float',
    ];
}
