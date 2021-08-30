<?php

namespace App\Http\Requests\OrderStatus;

use App\Models\Branch;
use App\Models\User;

class StoreScheduledRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'branch_id' => 'sometimes|nullable|exists:' . Branch::class . ',id',
            'user_id' => 'sometimes|nullable|exists:' . User::class . ',id',
            'schedule_date' => 'sometimes|nullable|after_or_equal:today',
            'order_status.note' => 'sometimes|nullable|string|max:255',
        ];
    }
}
