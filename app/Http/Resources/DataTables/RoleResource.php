<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Role $resource
 */
class RoleResource extends JsonResource
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
                'id' => 'role' . $this->resource->getKey(),
            ])->render(),
            'name' => $this->resource->name,
            'guard_name' => $this->resource->guard_name,
            'action' => view('components.datatables.link', [
                'url' => route('role.edit', $this->resource),
                'name' => __('Details'),
                'class' => 'btn btn-primary',
            ])->render(),
        ];
    }
}
