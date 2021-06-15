<?php

namespace App\Http\Requests\Auth;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisterRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'gender' => ['sometimes', 'nullable', Rule::in(Gender::toValues())],
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) {
                $validator = Validator::make(compact('phone'), [
                    'phone' => Rule::unique('users')->where(function ($query) use ($phone) {
                        $query->where('phone', PhoneNumber::make($phone, 'ID')->formatE164());
                    }),
                ]);

                if ($validator->fails()) {
                    $fail(trans('validation.phone', compact('attribute')));
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'agree_with_terms' => 'required|boolean|in:1',
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
            'password' => trans('Password'),
            'agree_with_terms' => trans('Terms of Service'),
        ];
    }

    /**
     * Register user based on the given request.
     *
     * @return \App\Models\User
     */
    public function register(): User
    {
        return tap(User::create($this->only([
            'name',
            'email',
            'password',
            'username',
            'fullname',
            'gender',
            'email',
            'phone_country',
            'phone',
            'password',
        ])), function (User $user) {
            $user->assignRole(Role::ROLE_ADMIN);
        });
    }
}
