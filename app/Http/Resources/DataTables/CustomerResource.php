<?php

namespace App\Http\Resources\DataTables;

use App\Models\Order;
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
        $elements = [];

        if ($request->user()->can('create', Order::class)) {
            $elements[] = view('admin.customer.form-create-order', [
                'customer' => $this->resource,
            ])->render();
        }

        if ($request->user()->can('view', $this->resource)) {
            $elements[] = view('components.datatables.link-show', [
                'url' => route('admin.customer.show', $this->resource),
            ])->render();
        }

        if ($request->user()->can('update', $this->resource)) {
            $elements[] = view('components.datatables.link-edit', [
                'url' => route('admin.customer.edit', $this->resource),
            ])->render();
        }

        if ($request->user()->can('delete', $this->resource)) {
            $elements[] = view('components.datatables.link-destroy', [
                'url' => route('admin.customer.destroy', $this->resource),
            ])->render();
        }

        return [
            'checkbox' => view('components.datatables.checkbox', [
                'value' => $this->resource->getKey(),
            ])->render(),
            'username' => $this->resource->username,
            'fullname' => $this->resource->fullname,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'action' => view('components.datatables.button-group', compact('elements'))->render(),
        ];
    }
}
