<?php

namespace App\Http\Requests\Customer;

use App\Enum\Gender;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class UpdateRequest extends AbstractRequest
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
                    ->where(request()->route('customer')->getKeyName(), '!=', request()->route('customer')->getKey())
                    ->count();

                if ($user > 0) {
                    $fail(trans('validation.unique', ['attribute' => static::getAttributes()[$attribute]]));
                }
            }];
        };

        return [
            'telegram_chat_id' => ['required', 'string', 'max:255', function ($attribute, $telegram_chat_id, $fail) {
                $validator = Validator::make(compact('telegram_chat_id'), [
                    $attribute => Rule::unique(Customer::class)->ignore($telegram_chat_id, $attribute),
                ]);

                if ($validator->fails()) {
                    $fail(trans('validation.unique', compact('attribute')));
                }
            }],
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'gender' => 'sometimes|nullable|enum:' . Gender::class,
            'email' => ['required', 'string', 'email', 'max:255', function ($attribute, $email, $fail) {
                $validator = Validator::make(compact('email'), [
                    $attribute => Rule::unique(Customer::class)->ignore($email, $attribute),
                ]);

                if ($validator->fails()) {
                    $fail(trans('validation.unique', compact('attribute')));
                }
            }],
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
     * Update the image file from the incoming request.
     *
     * @param  string  $key
     * @return string|null
     */
    public function updateImage(string $key = 'identitycard_image'): ?string
    {
        if (!$this->hasFile($key)) {
            return null;
        }

        /** @var \App\Models\Customer $model */
        $model = $this->route('customer');

        if ($model->getRawOriginal('identitycard_image') && $this->hasFile($key)) {
            Storage::delete(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $model->getRawOriginal('identitycard_image'));
        }

        $file = $this->file($key);

        $file->storeAs(
            Customer::IDENTITYCARD_IMAGE_PATH,
            $filename = ($this->input('value') . '.' . $file->getClientOriginalExtension())
        );

        return $filename;
    }
}
