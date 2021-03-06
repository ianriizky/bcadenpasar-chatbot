<?php

namespace App\Http\Requests\Auth;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Support\Auth\MultipleIdentifier;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    use MultipleIdentifier;

    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        return [
            'identifier' => static::getIdentifierRule(request()->input('identifier')),
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return [
            'identifier' => trans('Email') . ' / ' .trans('Phone Number') . ' / Username',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function validationData()
    {
        return [
            'identifier' => static::getIdentifierValue($this->input('identifier')),
            'password' => $this->input('password'),
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(static::getCredentials($this), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identifier' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        Event::dispatch(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')) . '|'. $this->ip();
    }
}
