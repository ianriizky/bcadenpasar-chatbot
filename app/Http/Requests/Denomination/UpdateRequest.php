<?php

namespace App\Http\Requests\Denomination;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Denomination;
use Illuminate\Support\Facades\Storage;

class UpdateRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function authorize()
    {
        return !is_null($this->user());
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return [
            'name' => trans('Name'),
            'value' => trans('Value'),
            'type' => trans('Type'),
            'quantity_per_bundle' => trans('Quantity Per Bundle'),
            'minimum_order_bundle' => trans('Minimum Order Bundle'),
            'maximum_order_bundle' => trans('Maximum Order Bundle'),
            'image' => trans('Image'),
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
            $filename = ($this->input('value') . '.' . $file->getClientOriginalExtension())
        );

        return $filename;
    }
}
