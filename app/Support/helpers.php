<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

if (! function_exists('terbilang')) {
    /**
     * Return the given value into readable number.
     *
     * @param  mixed  $value
     * @return string
     */
    function terbilang($value): string
    {
        $result = value(function () use ($value) {
            $angka = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

            $number = abs($value);

            switch (true) {
                case $number < 12:
                    return ' ' . $angka[$number];

                case $number < 20:
                    return terbilang($number - 10) . ' belas';

                case $number < 100:
                    return terbilang($number / 10) . ' puluh ' . terbilang($number % 10);

                case $number < 200:
                    return 'seratus ' . terbilang($number - 100);

                case $number < 1000:
                    return terbilang($number / 100) . ' ratus ' . terbilang($number % 100);

                case $number < 2000:
                    return 'seribu ' . terbilang($number - 1000);

                case $number < 1000000:
                    return terbilang($number / 1000) . ' ribu ' . terbilang($number % 1000);

                case $number < 1000000000:
                    return terbilang($number / 1000000) . ' juta ' . terbilang($number % 1000000);

                case $number < 1000000000000:
                    return terbilang($number / 1000000000) . ' milyar ' . terbilang($number % 1000000000);

                case $number < 1000000000000000:
                    return terbilang($number / 1000000000000) . ' trilyun ' . terbilang($number % 1000000000000);
            }
        });

        return trim(($value < 0 ? 'minus ' : '') . $result);
    }
}

if (! function_exists('greeting')) {
    /**
     * Return specific greeting based on the current hour.
     *
     * @param  \Illuminate\Support\Carbon|null  $date
     * @return string
     */
    function greeting(Carbon $date = null): string
    {
        $hour = ($date ?? Carbon::now())->format('H');

        switch (true) {
            case $hour < 12:
                return trans('Good Morning');

            case $hour < 15:
                return trans('Good Afternoon');

            case $hour < 18:
                return trans('Good Evening');

            default:
                return trans('Good Night');
        }
    }
}

if (! function_exists('telegram_url')) {
    /**
     * Return base telegram bot API url.
     *
     * @param  string  $method
     * @return string
     */
    function telegram_url(string $method): string
    {
        return sprintf('https://api.telegram.org/bot%s/%s', env('TELEGRAM_TOKEN'), $method);
    }
}

if (! function_exists('download_telegram_photo')) {
    /**
     * Download telegram photo based on the given photo list.
     *
     * @param  array  $photos (from $this->getBot()->getMessage()->getPayload())
     * @param  string  $path
     * @return string
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \InvalidArgumentException
     */
    function download_telegram_photo(array $photos, string $path)
    {
        $photos = Arr::sort($photos, fn ($photo) => $photo['file_size']);

        $photoId = Arr::last($photos)['file_id'];

        throw_unless($photoId, InvalidArgumentException::class, sprintf(
            'Telegram file_id is not found', $photoId
        ));

        $photoFilePath = Http::get(telegram_url('getFile'), [
            'file_id' => $photoId,
        ])->throw()->json('result.file_path', null);

        throw_unless($photoFilePath, InvalidArgumentException::class, sprintf(
            'Telegram file path for file_id %s is not found', $photoId
        ));

        $photo = file_get_contents(sprintf(
            'https://api.telegram.org/file/bot%s/%s',
            env('TELEGRAM_TOKEN'), $photoFilePath
        ));

        $filename = Str::random() . '.' . pathinfo($photoFilePath, PATHINFO_EXTENSION);

        Storage::put($path . '/' . $filename, $photo);

        return $filename;
    }
}

if (! function_exists('google_map_url')) {
    /**
     * Return google map url based on the given latitude and longitude.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  string  $zoom
     * @return string
     */
    function google_map_url(float $latitude, float $longitude, string $zoom = '20z'): string
    {
        return sprintf(
            'https://www.google.com/maps/@%s,%s,%s',
            $latitude, $longitude, $zoom
        );
    }
}

if (! function_exists('format_rupiah')) {
    /**
     * Return number in rupiah format.
     *
     * @param  float  $value
     * @param  string|null  $prefix
     * @return string
     */
    function format_rupiah(float $value, string $prefix = null): string
    {
        return $prefix . number_format($value, 0, ',', '.');
    }
}
