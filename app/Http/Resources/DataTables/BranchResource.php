<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Branch $resource
 */
class BranchResource extends JsonResource
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
                'id' => 'branch_' . $this->resource->getKey(),
            ])->render(),
            'name' => $this->resource->name,
            'address' => $this->resource->address,
            'google_map_url' => view('components.datatables.link', [
                'url' => $this->resource->google_map_url,
                'name' => $this->resource->address_latitude . ' | ' . $this->resource->address_longitude,
                'is_new_tab' => true,
            ])->render(),
            'action' => view('components.datatables.link', [
                'url' => route('branch.edit', $this->resource),
                'name' => __('Details'),
                'class' => 'btn btn-primary',
            ])->render(),
        ];
    }
}
