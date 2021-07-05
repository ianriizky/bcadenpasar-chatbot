<?php

namespace App\Http\Requests\Branch;

use App\Infrastructure\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:branches',
            'address' => 'required|string',
            'address_latitude' => 'required|numeric',
            'address_longitude' => 'required|numeric',
            'google_map_url' => 'sometimes|nullable|string|max:255',
        ];
    }
}
