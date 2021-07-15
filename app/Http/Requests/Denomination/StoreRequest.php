<?php

namespace App\Http\Requests\Denomination;

use App\Enum\DenominationType;
use App\Models\Denomination;

class StoreRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0|unique:' . Denomination::class,
            'type' => 'required|enum:' . DenominationType::class,
            'quantity_per_bundle' => 'required|numeric|min:0',
            'minimum_order_bundle' => 'required|numeric|min:0',
            'maximum_order_bundle' => 'required|numeric|gte:minimum_order_bundle',
            'image' => 'sometimes|nullable|image',
        ];
    }

    /**
     * Store the image file from the incoming request.
     *
     * @param  string  $key
     * @return string|null
     */
    public function storeImage(string $key = 'image'): ?string
    {
        if (!$this->hasFile($key)) {
            return null;
        }

        $file = $this->file($key);

        $file->storeAs(
            Denomination::IMAGE_PATH,
            $filename = ($this->input('value') . '.' . $file->getClientOriginalExtension())
        );

        return $filename;
    }
}
