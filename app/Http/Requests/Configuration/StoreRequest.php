<?php

namespace App\Http\Requests\Configuration;

use App\Models\Configuration;

class StoreRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'key' => 'required|string|max:255|unique:' . Configuration::class,
            'value' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
        ];
    }
}
