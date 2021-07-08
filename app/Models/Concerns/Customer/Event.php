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
        static::deleting(function (Customer $model) {
            Storage::delete(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $model->getRawOriginal('identitycard_image'));
        });
    }
}
