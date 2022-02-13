<?php

namespace App\Http\Requests\Item;

use App\Models\Denomination;
use Illuminate\Validation\Rule;

class UpdateRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getRules()
    {
        /** @var \Illuminate\Support\Collection $denominationIds */
        $denominationIds = static::getOrderFromRoute()->items()->pluck('denomination_id');
        $denominationIds = $denominationIds->reject(fn ($denominationId) =>
            $denominationId == static::getItemFromRoute()->denomination_id
        );

        return [
            'denomination_id' => ['required', 'exists:' . Denomination::class . ',id', Rule::notIn($denominationIds->toArray())],
            'bundle_quantity' => [
                'required', 'numeric',
                'min:' . static::getDenominationFromRequest()->minimum_order_bundle,
                'max:' . static::getDenominationFromRequest()->maximum_order_bundle,
            ],
            'quantity_per_bundle' => 'required|numeric|min:1',
        ];
    }
}
