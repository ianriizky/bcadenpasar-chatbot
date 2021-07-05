<?php

namespace App\Http\Requests\Branch;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique(Branch::class)->ignoreModel($this->route('branch'))],
            'address' => 'required|string',
            'address_latitude' => 'required|numeric',
            'address_longitude' => 'required|numeric',
            'google_map_url' => 'sometimes|nullable|string|max:255',
        ];
    }
}
