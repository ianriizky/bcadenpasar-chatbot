<?php

namespace App\Http\Requests\Role;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Role;

abstract class AbstractRequest extends FormRequest
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
    public function attributes()
    {
        return [
            'name' => trans('Name'),
            'guard_name' => trans('Guard Name'),
        ];
    }
}
