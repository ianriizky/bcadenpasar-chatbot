<?php

namespace App\Models\Concerns\Denomination;

use App\Models\Denomination;
use App\Support\Model\Event as ModelEvent;
use Illuminate\Support\Facades\Storage;

/**
 * @see \App\Models\Denomination
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
        static::saving(function (Denomination $model) {
            if (!$model->can_order_custom_quantity) {
                $model->minimum_order_quantity = $model->countMinimumOrderQuantityAttribute();
                $model->maximum_order_quantity = $model->countMaximumOrderQuantityAttribute();
            }
        });

        static::deleting(function (Denomination $model) {
            Storage::delete(Denomination::IMAGE_PATH . '/' . $model->getRawOriginal('image'));
        });
    }
}
