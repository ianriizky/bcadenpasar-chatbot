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
                'value' => $this->resource->getKey(),
            ])->render(),
            'name' => $this->resource->name,
            'guard_name' => $this->resource->guard_name,
            'action' => view('components.datatables.button-group', [
                'elements' => [
                    view('components.datatables.link-show', [
                        'url' => route('admin.role.edit', $this->resource),
                    ])->render(),
                    view('components.datatables.link-destroy', [
                        'url' => route('admin.role.destroy', $this->resource),
                    ])->render(),
                ],
            ])->render(),
        ];
    }
}
