<?php

namespace App\Http\Requests\Order;

use App\Enum\OrderStatus;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Denomination;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        $rules = [
            'customer_id' => 'required|exists:' . Customer::class . ',id',
            'user_id' => 'sometimes|nullable|exists:' . User::class . ',id',
            'branch_id' => 'sometimes|nullable|exists:' . Branch::class . ',id',
            'schedule_date' => 'sometimes|nullable|date|after_or_equal:today',
            'order_status.status' => 'sometimes|nullable|enum:' . OrderStatus::class,
            'order_status.note' => 'sometimes|nullable|string|max:255',
            'items' => 'sometimes|nullable|array',
        ];

        foreach (request()->input('items', []) as $index => $item) {
            /** @var \App\Models\Denomination $denomination */
            $denomination = Denomination::findOrFail($item['denomination_id'] ?? null);

            $rules['items.' . $index . '.denomination_id'] = 'required|distinct|exists:' . Denomination::class . ',id';
            $rules['items.' . $index . '.bundle_quantity'] = 'required|numeric|between:' . $denomination->minimum_order_bundle . ',' . $denomination->maximum_order_bundle;
        }

        return $rules;
    }
}