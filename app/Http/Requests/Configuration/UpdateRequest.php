<?php

namespace App\Http\Requests\Configuration;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Configuration;
use App\Models\Role;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function authorize()
    {
        return $this->user()->hasRole(Role::ROLE_ADMIN);
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'key' => ['required', 'string', 'max:255', Rule::unique(Configuration::class)->ignoreModel($this->route('configuration'))],
            'value' => 'required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return [
            'key' => trans('Key'),
            'value' => trans('Value'),
            'description' => trans('Description'),
        ];
    }
}
