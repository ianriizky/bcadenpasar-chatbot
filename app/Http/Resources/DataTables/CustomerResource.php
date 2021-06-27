<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Customer $resource
 */
class CustomerResource extends JsonResource
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
            'username' => $this->resource->username,
            'fullname' => $this->resource->fullname,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'action' => sprintf(<<<html
            <a href="%s" class="btn btn-secondary">Detail</a>
            html, route('customer.show', $this->resource)),
        ];
    }
}
