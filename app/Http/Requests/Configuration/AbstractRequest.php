<?php

namespace App\Http\Requests\Configuration;

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
            'key' => trans('Key'),
            'value' => trans('Value'),
            'description' => trans('Description'),
        ];
    }
}
