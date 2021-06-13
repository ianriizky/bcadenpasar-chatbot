<?php

namespace App\Http\Requests\Auth;

use App\Enum\Gender;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'gender' => ['sometimes', 'nullable', Rule::in(Gender::toValues())],
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', Rule::unique('users')->where(function ($query) {
                $query->where('phone', PhoneNumber::make($this->input('phone'), 'ID')->formatE164());
            })],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'agree_with_terms' => 'required|boolean|in:1',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
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
        /** @var \App\Models\User $user */
        $user = User::create($this->only([
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
        ]));

        $user->assignRole(Role::ROLE_ADMIN);

        return $user;
    }
}
