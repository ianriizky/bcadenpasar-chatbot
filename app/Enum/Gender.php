<?php

namespace App\Enum;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self male()
 * @method static self female()
 * @method static self undefined()
 */
class Gender extends Enum
{
    /**
     * {@inheritDoc}
     */
    protected static function labels(): array
    {
        return [
            'male' => trans('Male'),
            'female' => trans('Female'),
            'undefined' => trans('Undefined'),
        ];
    }

    /**
     * Return specified title based on the current enum.
     *
     * @return string|null
     */
    protected function getTitle(): ?string
    {
        return [
            'male' => trans('Mr.'),
            'female' => trans('Mrs.'),
        ][$this->value] ?? null;
    }
}
