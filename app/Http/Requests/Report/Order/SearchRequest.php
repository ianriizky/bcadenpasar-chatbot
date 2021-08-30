<?php

namespace App\Http\Requests\Report\Order;

use App\Infrastructure\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class SearchRequest extends FormRequest
{
    /**
     * Request value of "start_date".
     *
     * @var \Illuminate\Support\Carbon
     */
    public Carbon $start_date;

    /**
     * Request value of "end_date".
     *
     * @var \Illuminate\Support\Carbon
     */
    public Carbon $end_date;

    /**
     * {@inheritDoc}
     */
    public function validationData()
    {
        $this->parseDaterange($this->input('daterange'));

        return [
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:' . Carbon::today()->startOfMonth()->format('Y-m-d'),
            'end_date' => 'required|date_format:Y-m-d|before_or_equal:' . Carbon::today()->endOfMonth()->format('Y-m-d'),
        ];
    }

    /**
     * Parse the given daterange value into start_date and end_date format.
     *
     * @param  string  $value
     * @return array
     */
    protected function parseDaterange(string $value): array
    {
        return tap(array_pad(explode(' - ', $value), 2, null), function (array $daterange) {
            $this->start_date = Carbon::parse($daterange[0]);
            $this->end_date = Carbon::parse($daterange[1]);
        });
    }
}
