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
                'id' => 'customer_' . $this->resource->getKey(),
            ])->render(),
            'username' => $this->resource->username,
            'fullname' => $this->resource->fullname,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'action' => view('components.datatables.link', [
                'url' => route('customer.edit', $this->resource),
                'name' => __('Details'),
                'class' => 'btn btn-primary',
            ])->render(),
        ];
    }
}
