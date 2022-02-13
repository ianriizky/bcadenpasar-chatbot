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
        'quantity',
        'is_order_custom_quantity',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'quantity_per_bundle' => 'integer',
        'bundle_quantity' => 'integer',
        'quantity' => 'integer',
        'is_order_custom_quantity' => 'boolean',
    ];

    /**
     * Count value of "quantity" attribute.
     *
     * @return int
     */
    public function countQuantityAttribute(): int
    {
        return $this->quantity_per_bundle * $this->bundle_quantity;
    }
}
