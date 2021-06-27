<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Configuration $resource
 */
class ConfigurationResource extends JsonResource
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
                'id' => 'configuration_' . $this->resource->getKey(),
            ])->render(),
            'key' => $this->resource->key,
            'value' => $this->resource->value,
            'description' => $this->resource->description,
            'action' => view('components.datatables.link', [
                'url' => route('configuration.edit', $this->resource),
                'name' => __('Details'),
                'class' => 'btn btn-primary',
            ])->render(),
        ];
    }
}
