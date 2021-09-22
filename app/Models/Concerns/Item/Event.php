<?php

namespace App\Models\Concerns\Item;

use App\Models\Item;
use App\Support\Model\Event as ModelEvent;

/**
 * @see \App\Models\Item
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
        static::saving(function (Item $model) {
            if (is_null($model->getAttributeFromArray('quantity_per_bundle'))) {
                $model->quantity_per_bundle = $model->getDenominationRelationValue()->quantity_per_bundle;
            }

            if (!$model->is_order_custom_quantity) {
                $model->quantity = $model->countQuantityAttribute();
            }
        });
    }
}
