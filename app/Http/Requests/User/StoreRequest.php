<?php

namespace App\Http\Requests\User;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rules;
use Propaganistas\LaravelPhone\PhoneNumber;

class StoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'username' => 'required|string|max:255|unique:users',
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => 'required|string|email|max:255|unique:users',
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) {
                $user = User::where('phone', PhoneNumber::make($phone, request()->input('phone_country', env('PHONE_COUNTRY', 'ID')))->formatE164())->count();

                if ($user > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|exists:roles,name',
            'is_active' => 'required|boolean',
        ];
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
}
