<?php

namespace App\Models\Concerns\Branch;

/**
 * @property string $name
 * @property string $address
 * @property float $address_latitude
 * @property float $address_longitude
 * @property string $google_map_url
 *
 * @see \App\Models\Branch
 */
trait Attribute
{
    /**
     * Return "google_map_url" attribute value.
     *
     * @param  mixed  $value
     * @return string
     */
    public function getGoogleMapUrlAttribute($value): string
    {
        if (!is_null($value)) {
            return $value;
        }

        return google_map_url($this->address_latitude, $this->address_longitude);
    }
}
