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
            'checkbox' => view('components.datatables.checkbox', [
                'value' => $this->resource->getKey(),
            ])->render(),
            'username' => $this->resource->username,
            'fullname' => $this->resource->fullname,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'action' => view('components.datatables.button-group', [
                'elements' => [
                    view('components.datatables.link-show', [
                        'url' => route('admin.customer.edit', $this->resource),
                    ])->render(),
                    view('components.datatables.link-destroy', [
                        'url' => route('admin.customer.destroy', $this->resource),
                    ])->render(),
                ],
            ])->render(),
        ];
    }
}
