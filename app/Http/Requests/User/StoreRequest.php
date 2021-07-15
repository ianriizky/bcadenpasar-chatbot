<?php

namespace App\Http\Requests\User;

use App\Enum\Gender;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rules;
use Propaganistas\LaravelPhone\PhoneNumber;

class StoreRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'branch_id' => 'required|exists:' . Branch::class . ',id',
            'username' => 'required|string|max:255|unique:' . User::class,
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) {
                $user = User::where($attribute, PhoneNumber::make($phone, request()->input('phone_country', env('PHONE_COUNTRY', 'ID')))->formatE164())->count();

                if ($user > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|exists:' . Role::class . ',name',
            'is_active' => 'required|boolean',
        ];
    }
}
