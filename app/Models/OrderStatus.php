<?php

namespace App\Models;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends Model
{
    use HasFactory,
        Concerns\OrderStatus\Attribute,
        Concerns\OrderStatus\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'status',
        'note',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'status' => EnumOrderStatus::class,
    ];
}
