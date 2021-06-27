<?php

namespace App\Models;

use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory,
        Concerns\Order\Attribute,
        Concerns\Order\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'code',
        'schedule_date',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'schedule_date' => 'datetime',
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'status',
        // 'total_item_quantity_bundle',
        // 'total_item_value',
    ];
}
