<?php

namespace App\Http\Requests\Customer;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class StoreRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        $phoneRule = function () {
            return ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) {
                $validator = Validator::make(compact('phone'), [
                    'phone' => Rule::unique('customers')->where(function ($query) use ($phone) {
                        $query->where('phone', PhoneNumber::make($phone, 'ID')->formatE164());
                    }),
                ]);

                if ($validator->fails()) {
                    $fail(trans('validation.phone', compact('attribute')));
                }
            }];
        };

        return [
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'gender' => ['sometimes', 'nullable', Rule::in(Gender::toValues())],
            'email' => 'required|string|email|max:255|unique:customers,email',
            'phone_country' => 'sometimes|in:ID',
            'phone' => value($phoneRule),
            'whatasapp_phone_country' => 'sometimes|in:ID',
            'whatasapp_phone' => value($phoneRule),
            'accountnumber' => 'required_without:identitycardnumber|numeric',
            'identitycardnumber' => 'required_without:accountnumber|numeric',
            'identitycardimage' => 'sometimes|nullable|image',
            'location_latitude' => 'sometimes|nullable|numeric',
            'location_longitude' => 'sometimes|nullable|numeric',
        ];
    }

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
            'whatasapp_phone_country' => trans('Whatsapp Phone Country'),
            'whatasapp_phone' => trans('Whatsapp Phone Number'),
            'accountnumber' => trans('Account Number'),
            'identitycardnumber' => trans('Identity Card Number'),
            'identitycardimage' => trans('Identity Card Image'),
            'location_latitude' => trans('Latitude'),
            'location_longitude' => trans('Longitude'),
        ];
    }
}
