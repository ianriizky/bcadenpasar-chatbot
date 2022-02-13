<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Validation\Rule;

class UpdateRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(Role::class)->ignoreModel($this->route('role'))],
            'guard_name' => ['required', 'string', Rule::in(array_keys(config('auth.guards')))],
        ];
    }
}
