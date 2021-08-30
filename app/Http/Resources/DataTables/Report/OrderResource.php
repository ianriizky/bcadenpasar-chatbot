<?php

namespace App\Http\Resources\DataTables\Report;

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
            'code' => view('components.datatables.link', [
                'url' => route('admin.order.show', $this->resource->code),
                'name' => $this->resource->code,
            ])->render(),
            'customer_fullname' => view('components.datatables.link', [
                'url' => route('admin.customer.edit', $this->resource->customer),
                'name' => $this->resource->customer->fullname,
            ])->render(),
            'created_at' => $this->resource->created_at->translatedFormat('d F Y H:i:s'),
            'schedule_date' => $this->resource->schedule_date ? $this->resource->schedule_date->translatedFormat('d F Y H:i:s') : trans('Unscheduled'),
            'status' => $this->resource->status->label,
        ];
    }
}
