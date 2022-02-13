<?php

namespace App\Http\Requests\Configuration;

use App\Models\Configuration;
use Illuminate\Validation\Rule;

class UpdateRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'key' => ['required', 'string', 'max:255', Rule::unique(Configuration::class)->ignoreModel($this->route('configuration'))],
            'value' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
        ];
    }
}
