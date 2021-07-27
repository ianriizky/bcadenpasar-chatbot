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
        'name',
        'value',
        'type',
        'quantity_per_bundle',
        'minimum_order_bundle',
        'maximum_order_bundle',
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
}
