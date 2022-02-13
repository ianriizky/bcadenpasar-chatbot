<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Validation\Rule;

class StoreRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:' . Role::class,
            'guard_name' => ['required', 'string', Rule::in(array_keys(config('auth.guards')))],
        ];
    }
}
