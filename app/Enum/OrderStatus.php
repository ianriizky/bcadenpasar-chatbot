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

    /**
     * Return fontawesome icon based on the enum value.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return [
            'draft' => 'fa-calendar',
            'on_progress' => 'fa-calendar-alt',
            'scheduled' => 'fa-calendar-plus',
            'rescheduled' => 'fa-calendar-week',
            'canceled' => 'fa-calendar-times',
            'finished' => 'fa-calendar-check',
        ][$this->value];
    }

    /**
     * Return bootstrap color based on the enum value.
     *
     * @return string
     */
    public function getColor(): string
    {
        return [
            'draft' => 'primary',
            'on_progress' => 'primary',
            'scheduled' => 'success',
            'rescheduled' => 'warning',
            'canceled' => 'danger',
            'finished' => 'success',
        ][$this->value];
    }
}
