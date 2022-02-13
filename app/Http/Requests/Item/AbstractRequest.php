<?php

namespace App\Http\Requests\Item;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Denomination;
use App\Models\Item;
use App\Models\Order;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getAttributes()
    {
        return [
            'order_id' => trans('admin-lang.order'),
            'denomination_id' => trans('admin-lang.denomination'),
            'quantity_per_bundle' => trans('Quantity Per Bundle'),
            'bundle_quantity' => trans('Bundle Quantity'),
        ];
    }

    /**
     * Return order model instance from the route.
     *
     * @return \App\Models\Order
     */
    public static function getOrderFromRoute(): Order
    {
        return request()->route('order');
    }

    /**
     * Return item model instance from the route.
     *
     * @return \App\Models\Item
     */
    public static function getItemFromRoute(): Item
    {
        return request()->route('item');
    }

    /**
     * Return denomination model instance from the request.
     *
     * @param  string  $key
     * @return \App\Models\Denomination
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function getDenominationFromRequest(string $key = 'denomination_id'): Denomination
    {
        return Denomination::findOrFail(request()->input($key));
    }
}
