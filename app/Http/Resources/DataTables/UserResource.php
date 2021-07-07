<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\User $resource
 */
class UserResource extends JsonResource
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
            'branch_name' => view('components.datatables.link', [
                'url' => route('admin.branch.edit', $this->resource->branch),
                'name' => $this->resource->branch->name,
            ])->render(),
            'username' => $this->resource->username,
            'fullname' => $this->resource->fullname,
            'email' => $this->resource->email,
            'is_active' => $this->resource->is_active_badge,
            'action' => view('components.datatables.button-group', [
                'elements' => [
                    view('components.datatables.link-show', [
                        'url' => route('admin.user.edit', $this->resource),
                    ])->render(),
                    view('components.datatables.link-destroy', [
                        'url' => route('admin.user.destroy', $this->resource),
                    ])->render(),
                ],
            ])->render(),
        ];
    }
}
