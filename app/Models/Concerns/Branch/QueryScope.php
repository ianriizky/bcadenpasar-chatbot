<?php

namespace App\Models\Concerns\Branch;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|static nearestLocation()
 *
 * @see \App\Models\Branch
 * @see \Illuminate\Database\Eloquent\Builder @callScope()
 */
trait QueryScope
{
    /**
     * Scope a query to find nearest location based on the given latitude and longitude.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $latitude
     * @param  mixed  $longitude
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearestLocation(Builder $query, $latitude, $longitude)
    {
        $fieldLatitude = 'address_latitude';
        $fieldLongitude = 'address_longitude';

        $selectQuery = sprintf(
            '*, ' .
            '6371 * acos(cos(radians(?)) ' .
            '* cos(radians(%s)) ' .
            '* cos(radians(%s) - radians(?)) ' .
            '+ sin(radians(?)) ' .
            '* sin(radians(%s))) AS distance',
            $fieldLatitude, $fieldLongitude, $fieldLatitude
        );

        return $query->selectRaw(
            $selectQuery,
            [$latitude, $longitude, $latitude]
        )->orderBy('distance');
    }
}
