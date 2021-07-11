<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Order $resource
 */
class OrderResource extends JsonResource
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
            'detail' => view('components.datatables.button-row-child', [
                'url' => route('admin.configuration.datatable-row-child', $this->resource),
            ])->render(),
            'code' => $this->resource->code,
            'customer_fullname' => view('components.datatables.link', [
                'url' => route('admin.customer.edit', $this->resource->customer),
                'name' => $this->resource->customer->fullname,
            ])->render(),
            'schedule_date' => $this->resource->schedule_date,
            'status' => $this->resource->status->label,
            'action' => view('components.datatables.button-group', [
                'elements' => [
                    view('components.datatables.link-show', [
                        'url' => route('admin.order.edit', $this->resource),
                    ])->render(),
                    view('components.datatables.link-destroy', [
                        'url' => route('admin.order.destroy', $this->resource),
                    ])->render(),
                ],
            ])->render(),
        ];
    }
}
