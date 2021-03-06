<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Configuration $resource
 */
class ConfigurationResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request)
    {
        $elements = [];

        if ($request->user()->can('update', $this->resource)) {
            $elements[] = view('components.datatables.link-edit', [
                'url' => route('admin.configuration.edit', $this->resource),
            ])->render();
        }

        if ($request->user()->can('delete', $this->resource)) {
            $elements[] = view('components.datatables.link-destroy', [
                'url' => route('admin.configuration.destroy', $this->resource),
            ])->render();
        }

        return [
            'checkbox' => view('components.datatables.checkbox', [
                'value' => $this->resource->getKey(),
            ])->render(),
            'key' => $this->resource->key,
            'value' => $this->resource->value,
            'description' => $this->resource->description,
            'action' => view('components.datatables.button-group', compact('elements'))->render(),
        ];
    }
}
