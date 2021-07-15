<?php

namespace App\Models\Concerns\Order;

use App\Models\Order;
use App\Support\Model\Event as ModelEvent;

/**
 * @see \App\Models\Order
 */
trait Event
{
    use ModelEvent;

    /**
     * Boot the trait on the model.
     *
     * @return void
     */
    protected static function bootEvent()
    {
        static::creating(function (Order $model) {
            if (is_null($model->code)) {
                $model->code = $model->generateCode();
            }
        });

        static::deleting(function (Order $model) {
            $model->items->map->delete();
        });
    }
}
