<?php

namespace App\Http\Requests\Branch;

use App\Models\Branch;

class StoreRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:' . Branch::class,
            'address' => 'required|string',
            'address_latitude' => 'required|numeric',
            'address_longitude' => 'required|numeric',
            'google_map_url' => 'sometimes|nullable|string|max:255',
        ];
    }
}
