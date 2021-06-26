<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory,
        Concerns\Item\Attribute,
        Concerns\Item\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'order_bundle',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'order_bundle' => 'integer',
    ];
}
