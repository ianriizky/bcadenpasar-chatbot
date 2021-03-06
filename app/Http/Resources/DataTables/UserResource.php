<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\User $resource
 */
class UserResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request)
    {
        $elements = [];

        if ($request->user()->can('view', $this->resource)) {
            $elements[] = view('components.datatables.link-show', [
                'url' => route('admin.user.show', $this->resource),
            ])->render();
        }

        if ($request->user()->can('update', $this->resource)) {
            $elements[] = view('components.datatables.link-edit', [
                'url' => route('admin.user.edit', $this->resource),
            ])->render();
        }

        if ($request->user()->can('delete', $this->resource)) {
            $elements[] = view('components.datatables.link-destroy', [
                'url' => route('admin.user.destroy', $this->resource),
            ])->render();
        }

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
            'action' => view('components.datatables.button-group', compact('elements'))->render(),
        ];
    }
}
