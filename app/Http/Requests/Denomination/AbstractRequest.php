<?php

namespace App\Http\Requests\Denomination;

use App\Infrastructure\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function authorize()
    {
        return !is_null($this->user());
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return [
            'name' => trans('Name'),
            'value' => trans('Value'),
            'type' => trans('Type'),
            'quantity_per_bundle' => trans('Quantity Per Bundle'),
            'minimum_order_bundle' => trans('Minimum Order Bundle'),
            'maximum_order_bundle' => trans('Maximum Order Bundle'),
            'image' => trans('Image'),
        ];
    }
}
