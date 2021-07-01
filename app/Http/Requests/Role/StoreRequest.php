<?php

namespace App\Http\Requests\Role;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Role;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:roles',
            'guard_name' => ['required', 'string', Rule::in(array_keys(config('auth.guards')))],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return [
            'name' => trans('Name'),
            'guard_name' => trans('Guard Name'),
        ];
    }
}
