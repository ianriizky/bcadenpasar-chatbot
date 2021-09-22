<?php

namespace App\Http\Requests\Denomination;

use App\Enum\DenominationType;
use App\Models\Denomination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'key' => ['required', 'string', 'max:255', Rule::unique(Denomination::class)->ignoreModel($this->route('denomination'))],
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'type' => 'required|enum:' . DenominationType::class,
            'quantity_per_bundle' => 'required|numeric|min:0',
            'minimum_order_bundle' => 'required|numeric|min:0',
            'maximum_order_bundle' => 'required|numeric|gte:minimum_order_bundle',
            'minimum_order_quantity' => 'sometimes|nullable|numeric|min:0',
            'maximum_order_quantity' => 'sometimes|nullable|numeric|gte:minimum_order_quantity',
            'can_order_custom_quantity' => 'required|boolean',
            'is_visible' => 'required|boolean',
            'image' => 'sometimes|nullable|image',
        ];
    }

    /**
     * Update the image file from the incoming request.
     *
     * @param  string  $key
     * @return string|null
     */
    public function updateImage(string $key = 'image'): ?string
    {
        if (!$this->hasFile($key)) {
            return null;
        }

        /** @var \App\Models\Denomination $model */
        $model = $this->route('denomination');

        if ($model->getRawOriginal('image') && $this->hasFile($key)) {
            Storage::delete(Denomination::IMAGE_PATH . '/' . $model->getRawOriginal('image'));
        }

        $file = $this->file($key);

        $file->storeAs(
            Denomination::IMAGE_PATH,
            $filename = ($this->input('type') . '-' . $this->input('value') . '.' . $file->getClientOriginalExtension())
        );

        return $filename;
    }
}
