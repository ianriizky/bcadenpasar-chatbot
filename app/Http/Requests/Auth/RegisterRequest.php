<?php

namespace App\Http\Requests\Auth;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
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
            'branch_name' => 'required|exists:' . Branch::class . ',name',
            'username' => 'required|string|max:255|unique:' . User::class,
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) {
                $user = User::where('phone', PhoneNumber::make($phone, request()->input('phone_country', env('PHONE_COUNTRY', 'ID')))->formatE164())->count();

                if ($user > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
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
            'branch_name' => trans('admin-lang.branch'),
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
        $attributes = $this->only([
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
        ]);

        /** @var \App\Models\User $user */
        $user = User::make($attributes);

        $user->setBranchRelationValue(Branch::where('name', $this->input('branch_name'))->first())->save();

        $user->syncRoles(Role::ROLE_STAFF);

        return $user;
    }
}
