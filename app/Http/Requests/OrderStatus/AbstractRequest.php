<?php

namespace App\Http\Requests\OrderStatus;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Branch;
use Illuminate\Support\Carbon;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getAttributes()
    {
        return [
            'branch_id' => trans('admin-lang.branch'),
            'schedule_date' => trans('Schedule Date'),
            'order_status.note' => trans('Note'),
        ];
    }

    /**
     * Return branch model instance from the request.
     *
     * @param  string  $key
     * @return \App\Models\Branch
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getBranchFromRequest(string $key = 'branch_id'): Branch
    {
        return Branch::findOrFail($this->input($key));
    }

    /**
     * Return "schedule_date" value from the request.
     *
     * @param  string  $key
     * @return \Illuminate\Support\Carbon
     */
    public function getScheduleDate(string $key = 'schedule_date'): Carbon
    {
        return Carbon::parse($this->input($key, 'now'));
    }
}
