<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Denomination $resource
 */
class DenominationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'checkbox' => view('components.datatables.checkbox', [
                'id' => 'denomination_' . $this->resource->getKey(),
            ])->render(),
            'name' => $this->resource->name,
            'value' => $this->resource->value,
            'type' => $this->resource->type->label,
            'quantity_per_bundle' => $this->resource->quantity_per_bundle,
            'action' => view('components.datatables.link', [
                'url' => route('denomination.edit', $this->resource),
                'name' => __('Details'),
                'class' => 'btn btn-primary',
            ])->render(),
        ];
    }
}
