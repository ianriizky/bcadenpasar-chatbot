<?php

namespace App\Http\Requests\Customer;

use App\Infrastructure\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        return [
            'accountnumber' => 'required_without:identitycardnumber|numeric',
            'identitycardnumber' => 'required_without:accountnumber|numeric',
            'identitycardimage' => 'sometimes|nullable|image',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getAttributes()
    {
        return [
            'accountnumber' => trans('Account Number'),
            'identitycardnumber' => trans('Identity Card Number'),
            'identitycardimage' => trans('Identity Card Image'),
        ];
    }
}
