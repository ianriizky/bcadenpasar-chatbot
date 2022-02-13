<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @property \App\Models\Branch $resource
 */
class BranchResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request)
    {
        $elements = [];

        if ($request->user()->can('view', $this->resource)) {
            $elements[] = view('components.datatables.link-show', [
                'url' => route('admin.branch.show', $this->resource),
            ])->render();
        }

        if ($request->user()->can('update', $this->resource)) {
            $elements[] = view('components.datatables.link-edit', [
                'url' => route('admin.branch.edit', $this->resource),
            ])->render();
        }

        if ($request->user()->can('delete', $this->resource)) {
            $elements[] = view('components.datatables.link-destroy', [
                'url' => route('admin.branch.destroy', $this->resource),
            ])->render();
        }

        return [
            'checkbox' => view('components.datatables.checkbox', [
                'value' => $this->resource->getKey(),
            ])->render(),
            'name' => $this->resource->name,
            'address' => $this->resource->address,
            'google_map_url' => view('components.datatables.link', [
                'url' => $this->resource->google_map_url,
                'name' => Str::limit($this->resource->google_map_url, 50),
                'is_new_tab' => true,
            ])->render(),
            'action' => view('components.datatables.button-group', compact('elements'))->render(),
        ];
    }
}
