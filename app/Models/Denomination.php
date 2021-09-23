<?php

namespace App\Models;

use App\Enum\DenominationType;
use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denomination extends Model
{
    use HasFactory,
        Concerns\Denomination\Attribute,
        Concerns\Denomination\Event,
        Concerns\Denomination\QueryScope,
        Concerns\Denomination\Relation;

    /**
     * Filepath value for image.
     *
     * @var string
     */
    const IMAGE_PATH = 'denomination';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'code',
        'name',
        'value',
        'type',
        'quantity_per_bundle',
        'minimum_order_bundle',
        'maximum_order_bundle',
        'minimum_order_quantity',
        'maximum_order_quantity',
        'can_order_custom_quantity',
        'is_visible',
        'image',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'value' => 'float',
        'type' => DenominationType::class,
        'quantity_per_bundle' => 'integer',
        'minimum_order_bundle' => 'integer',
        'maximum_order_bundle' => 'integer',
        'minimum_order_quantity' => 'integer',
        'maximum_order_quantity' => 'integer',
        'can_order_custom_quantity' => 'boolean',
        'is_visible' => 'boolean',
    ];

    /**
     * Determine whether the given value is between the minimum and maximum order bundle.
     *
     * @param  int  $value
     * @param  bool  $equal
     * @return bool
     */
    public function isBetweenOrderBundle(int $value, bool $equal = true): bool
    {
        if ($equal) {
            return
                $value >= $this->minimum_order_bundle &&
                $value <= $this->maximum_order_bundle;
        }

        return
            $value > $this->minimum_order_bundle &&
            $value < $this->maximum_order_bundle;
    }

    /**
     * Determine whether the given value is between the minimum and maximum order quantity.
     *
     * @param  int  $value
     * @param  bool  $equal
     * @return bool
     */
    public function isBetweenOrderQuantity(int $value, bool $equal = true): bool
    {
        if ($equal) {
            return
                $value >= $this->minimum_order_quantity &&
                $value <= $this->maximum_order_quantity;
        }

        return
            $value > $this->minimum_order_quantity &&
            $value < $this->maximum_order_quantity;
    }

    /**
     * Count value of "minimum_order_quantity" attribute.
     *
     * @return int
     */
    public function countMinimumOrderQuantityAttribute(): int
    {
        return $this->quantity_per_bundle * $this->minimum_order_bundle;
    }

    /**
     * Count value of "maximum_order_quantity" attribute.
     *
     * @return int
     */
    public function countMaximumOrderQuantityAttribute(): int
    {
        return $this->quantity_per_bundle * $this->maximum_order_bundle;
    }
}
