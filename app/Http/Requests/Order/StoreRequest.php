<?php

namespace App\Http\Requests\Order;

use App\Enum\OrderStatus;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;

class StoreRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return !Order::isMaximumOrderPerDayExceeded();
    }

    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        return [
            'customer_id' => 'required|exists:' . Customer::class . ',id',
            'user_id' => 'sometimes|nullable|exists:' . User::class . ',id',
            'branch_id' => 'sometimes|nullable|exists:' . Branch::class . ',id',
            'schedule_date' => 'sometimes|nullable|date|after_or_equal:today',
            'order_status.status' => 'sometimes|nullable|enum:' . OrderStatus::class,
            'order_status.note' => 'sometimes|nullable|string|max:255',
        ];
    }
}
