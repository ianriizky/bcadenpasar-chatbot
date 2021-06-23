<?php

namespace App\Models\Concerns\Customer;

use Illuminate\Support\Facades\Storage;

/**
 * @property string $telegram_chat_id
 * @property string $username
 * @property string $fullname
 * @property string $gender
 * @property string $email
 * @property string $phone_country
 * @property \Propaganistas\LaravelPhone\PhoneNumber $phone
 * @property string $whatsapp_phone_country
 * @property \Propaganistas\LaravelPhone\PhoneNumber $whatsapp_phone
 * @property string $account_number
 * @property string $identitycard_number
 * @property string|null $identitycard_image
 * @property float|null $location_latitude
 * @property float|null $location_longitude
 * @property-read string|null $google_map_url
 *
 * @see \App\Models\Customer
 */
trait Attribute
{
    /**
     * Return "identitycard_image" attribute value.
     *
     * @param  mixed  $value
     * @return string|null
     */
    public function getIdentitycardImageAttribute($value)
    {
        if ($value) {
            return Storage::url(static::IDENTITYCARD_IMAGE_PATH . '/' . $value);
        }

        return null;
    }

    /**
     * Return "google_map_url" attribute value.
     *
     * @return string|null
     */
    public function getGoogleMapUrlAttribute(): ?string
    {
        if (!is_null($this->location_latitude) && !is_null($this->location_longitude)) {
            return sprintf(
                'https://www.google.com/maps/@%s,%s,%s',
                $this->location_latitude, $this->location_longitude,
                '20z'
            );
        }

        return null;
    }
}
