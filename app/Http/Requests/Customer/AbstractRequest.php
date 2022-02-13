<?php

namespace App\Http\Requests\Customer;

use App\Infrastructure\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getAttributes()
    {
        return [
            'username' => trans('Username'),
            'fullname' => trans('Full name'),
            'gender' => trans('Gender'),
            'email' => trans('Email'),
            'phone_country' => trans('Phone Country'),
            'phone' => trans('Phone Number'),
            'whatsapp_phone_country' => trans('Whatsapp Phone Country'),
            'whatsapp_phone' => trans('Whatsapp Phone Number'),
            'account_number' => trans('Account Number'),
            'identitycard_number' => trans('Identity Card Number'),
            'identitycard_image' => trans('Identity Card Image'),
            'location_latitude' => trans('Latitude'),
            'location_longitude' => trans('Longitude'),
        ];
    }
}
