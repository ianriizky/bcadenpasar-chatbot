<?php

namespace App\Http\Requests\User;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Branch;

abstract class AbstractRequest extends FormRequest
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
    public function attributes()
    {
        return [
            'branch_id' => trans('admin-lang.branch'),
            'username' => trans('Username'),
            'fullname' => trans('Full name'),
            'gender' => trans('Gender'),
            'email' => trans('Email'),
            'phone_country' => trans('Phone Country'),
            'phone' => trans('Phone Number'),
            'role' => trans('Role'),
            'is_active' => trans('Active'),
        ];
    }

    /**
     * Return branch model based on the request.
     *
     * @param  string  $key
     * @return \App\Models\Branch
     */
    public function getBranch(string $key = 'branch_id'): Branch
    {
        return Branch::find($this->input($key));
    }
}
