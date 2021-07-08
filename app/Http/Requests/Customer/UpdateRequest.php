<?php

namespace App\Http\Requests\Customer;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class UpdateRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        $phoneRule = function () {
            return ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) {
                $country = $attribute === 'phone'
                    ? request()->input('phone_country', env('PHONE_COUNTRY', 'ID'))
                    : request()->input('whatsapp_phone_country', env('PHONE_COUNTRY', 'ID'));

                $user = Customer::where($attribute, PhoneNumber::make($phone, $country)->formatE164())
                    ->where(request()->route('customer')->getKeyName(), '!=', request()->route('customer')->getKey())
                    ->count();

                if ($user > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
                }
            }];
        };

        return [
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => ['required', 'string', 'email', 'max:255', function ($attribute, $email, $fail) {
                $validator = Validator::make(compact('email'), [
                    $attribute => Rule::unique(Customer::class)->ignore($email, $attribute),
                ]);

                if ($validator->fails()) {
                    $fail(trans('validation.unique', compact('attribute')));
                }
            }],
            'phone_country' => 'sometimes|in:ID',
            'phone' => value($phoneRule),
            'whatsapp_phone_country' => 'sometimes|in:ID',
            'whatsapp_phone' => value($phoneRule),
            'account_number' => ['required_without:identitycard_number'] + (request()->filled('account_number') ? ['numeric'] : []),
            'identitycard_number' => ['required_without:account_number'] + (request()->filled('identitycard_number') ? ['numeric'] : []),
            'identitycard_image' => 'sometimes|nullable|image',
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
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
            'whatsapp_phone_country' => trans('Whatsapp Phone Country'),
            'whatsapp_phone' => trans('Whatsapp Phone Number'),
            'account_number' => trans('Account Number'),
            'identitycard_number' => trans('Identity Card Number'),
            'identitycard_image' => trans('Identity Card Image'),
            'location_latitude' => trans('Latitude'),
            'location_longitude' => trans('Longitude'),
        ];
    }

    /**
     * Update the image file from the incoming request.
     *
     * @param  string  $key
     * @return string|null
     */
    public function updateImage(string $key = 'identitycard_image'): ?string
    {
        if (!$this->hasFile($key)) {
            return null;
        }

        /** @var \App\Models\Customer $model */
        $model = $this->route('customer');

        if ($model->getRawOriginal('identitycard_image') && $this->hasFile($key)) {
            Storage::delete(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $model->getRawOriginal('identitycard_image'));
        }

        $file = $this->file($key);

        $file->storeAs(
            Customer::IDENTITYCARD_IMAGE_PATH,
            $filename = ($this->input('value') . '.' . $file->getClientOriginalExtension())
        );

        return $filename;
    }
}
