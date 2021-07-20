<?php

namespace App\Support\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

trait MultipleIdentifier
{
    /**
     * Return the identifier field type based on the given request.
     *
     * @param  mixed  $value
     * @return string
     */
    protected static function getIdentifierField($value): string
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return 'email';
        }

        if (!Validator::make(['phone' => $value], ['phone' => 'phone:ID'])->fails()) {
            return 'phone';
        }

        return 'username';
    }

    /**
     * Return list of necessary identifier rule for validation.
     *
     * @param  mixed  $value
     * @return array
     */
    protected static function getIdentifierRule($value): array
    {
        $field = static::getIdentifierField($value);

        $rules = ['required', 'string', 'exists:' . User::class . ',' . $field];

        switch ($field) {
            case 'email':
                $rules[] = 'email';
                break;

            case 'phone':
                $rules[] = 'phone:ID';
                break;
        }

        return $rules;
    }

    /**
     * Return identifier value for authentication process.
     *
     * @param  mixed  $value
     * @param  string  $country
     * @return mixed
     */
    protected static function getIdentifierValue($value, string $country = 'ID')
    {
        $field = static::getIdentifierField($value);

        if ($field === 'phone') {
            return (string) PhoneNumber::make($value, $country);
        }

        return $value;
    }

    /**
     * Return identifier and password data as the credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $identifierField
     * @return array
     */
    protected static function getCredentials($request, string $identifiedField = 'identifier'): array
    {
        $value = static::getIdentifierValue($request->input($identifiedField));

        return [
            static::getIdentifierField($value) => $value,
            'password' => $request->input('password'),
        ];
    }
}
