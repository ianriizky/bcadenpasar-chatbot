<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory,
        Concerns\Item\Attribute,
        Concerns\Item\Event,
        Concerns\Item\QueryScope,
        Concerns\Item\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'quantity_per_bundle',
        'bundle_quantity',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'quantity_per_bundle' => 'integer',
        'bundle_quantity' => 'integer',
    ];
}
