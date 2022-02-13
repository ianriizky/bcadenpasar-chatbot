<?php

namespace App\Enum;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self coin()
 * @method static self banknote()
 */
class DenominationType extends Enum
{
    /**
     * {@inheritDoc}
     */
    protected static function labels(): array
    {
        return [
            'coin' => trans('denomination-type.coin'),
            'banknote' => trans('denomination-type.banknote'),
        ];
    }
}
