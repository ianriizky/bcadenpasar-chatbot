<?php

namespace App\Enum;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self draft()
 * @method static self on_progress()
 * @method static self scheduled()
 * @method static self rescheduled()
 * @method static self canceled()
 * @method static self finished()
 */
class OrderStatus extends Enum
{
    /**
     * {@inheritDoc}
     */
    protected static function labels(): array
    {
        return [
            'draft' => trans('order-status.draft'),
            'on_progress' => trans('order-status.on_progress'),
            'scheduled' => trans('order-status.scheduled'),
            'rescheduled' => trans('order-status.rescheduled'),
            'canceled' => trans('order-status.canceled'),
            'finished' => trans('order-status.finished'),
        ];
    }
}
