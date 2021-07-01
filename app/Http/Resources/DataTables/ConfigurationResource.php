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
                'value' => $this->resource->getKey(),
            ])->render(),
            'key' => $this->resource->key,
            'value' => $this->resource->value,
            'description' => $this->resource->description,
            'action' => view('components.datatables.button-group', [
                'elements' => [
                    view('components.datatables.link-show', [
                        'url' => route('admin.configuration.edit', $this->resource),
                    ])->render(),
                    view('components.datatables.link-destroy', [
                        'url' => route('admin.configuration.destroy', $this->resource),
                    ])->render(),
                ],
            ])->render(),
        ];
    }
}
