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
            'checkbox' => <<<html
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
            </div>
            html,
            'branch_name' => $this->resource->branch->name,
            'username' => $this->resource->username,
            'fullname' => $this->resource->fullname,
            'email' => $this->resource->email,
            'is_active' => $this->resource->is_active_badge,
            'action' => sprintf(<<<html
            <a href="%s" class="btn btn-secondary">Detail</a>
            html, route('user.show', $this->resource)),
        ];
    }
}
