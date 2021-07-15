<?php

namespace App\Http\Requests\Customer;

use App\Enum\Gender;
use App\Models\Customer;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class StoreRequest extends AbstractRequest
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
                    ->count();

                if ($user > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
                }
            }];
        };

        return [
            'telegram_chat_id' => 'required|string|max:255|unique:' . Customer::class,
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => 'required|string|email|max:255|unique:' . Customer::class,
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
     * Store the image file from the incoming request.
     *
     * @param  string  $key
     * @return string|null
     */
    public function storeImage(string $key = 'identitycard_image'): ?string
    {
        if (!$this->hasFile($key)) {
            return null;
        }

        $file = $this->file($key);

        $file->storeAs(
            Customer::IDENTITYCARD_IMAGE_PATH,
            $filename = (Str::random() . '.' . $file->getClientOriginalExtension())
        );

        return $filename;
    }
}
