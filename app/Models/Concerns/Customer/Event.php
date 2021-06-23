<?php

namespace App\Models\Concerns\Customer;

use App\Models\Customer;
use App\Support\Model\Event as ModelEvent;
use Illuminate\Support\Facades\Storage;

/**
 * @see \App\Models\Customer
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
        static::saving(function (Customer $model) {
            if (is_null($model->identitycard_image)) {
                Storage::delete(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $model->getRawOriginal('identitycard_image'));
            }
        });
    }
}
