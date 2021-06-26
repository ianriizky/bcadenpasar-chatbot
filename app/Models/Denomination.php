<?php

namespace App\Models;

use App\Enum\DenominationType;
use App\Infrastructure\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denomination extends Model
{
    use HasFactory,
        Concerns\Denomination\Attribute,
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
}
