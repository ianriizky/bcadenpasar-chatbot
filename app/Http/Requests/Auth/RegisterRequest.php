<?php

namespace App\Http\Requests\Auth;

use App\Enum\Gender;
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
            'gender' => ['sometimes', Rule::in(Gender::toValues())],
            'email' => 'required|string|email|max:255|unique:users',
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', Rule::unique('users')->where(function ($query) {
                $query->where('phone', PhoneNumber::make($this->input('phone'), 'ID')->formatE164());
            })],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Register user based on the given request.
     *
     * @return \App\Models\User
     */
    public function register(): User
    {
        return User::create($this->only([
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
    }
}
