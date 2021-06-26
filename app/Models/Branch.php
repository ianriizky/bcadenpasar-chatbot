<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
