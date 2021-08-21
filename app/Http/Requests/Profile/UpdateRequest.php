<?php

namespace App\Http\Requests\Profile;

use App\Enum\Gender;
use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Propaganistas\LaravelPhone\PhoneNumber;

class UpdateRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignoreModel($user)],
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignoreModel($user)],
            'phone_country' => 'sometimes|in:ID',
            'phone' => ['required', 'string', 'phone:ID', function ($attribute, $phone, $fail) use ($user) {
                $userCount = User::where($attribute, PhoneNumber::make($phone, request()->input('phone_country', env('PHONE_COUNTRY', 'ID')))->formatE164())
                    ->where($user->getKeyName(), '!=', $user->getKey())
                    ->count();

                if ($userCount > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
                }
            }],
            'password' => ['sometimes', 'nullable', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function validated()
    {
        $validated = parent::validated();

        if ($this->isNotFilled('password')) {
            Arr::forget($validated, 'password');
        }

        return $validated;
    }
}
