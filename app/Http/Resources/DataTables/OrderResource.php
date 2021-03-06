<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Order $resource
 */
class OrderResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request)
    {
        $elements = [];

        if ($request->user()->can('view', $this->resource)) {
            $elements[] = view('components.datatables.link-show', [
                'url' => route('admin.order.show', $this->resource),
            ])->render();
        }

        if ($request->user()->can('delete', $this->resource)) {
            $elements[] = view('components.datatables.link-destroy', [
                'url' => route('admin.order.destroy', $this->resource),
            ])->render();
        }

        return [
            'checkbox' => view('components.datatables.checkbox', [
                'value' => $this->resource->getKey(),
            ])->render(),
            'detail' => view('components.datatables.button-row-child', [
                'url' => route('admin.order.datatable-row-child', $this->resource),
            ])->render(),
            'code' => $this->resource->code,
            'customer_fullname' => view('components.datatables.link', [
                'url' => route('admin.customer.edit', $this->resource->customer),
                'name' => $this->resource->customer->fullname,
            ])->render(),
            'created_at' => $this->resource->created_at->translatedFormat('d F Y H:i:s'),
            'schedule_date' => $this->resource->schedule_date ? $this->resource->schedule_date->translatedFormat('d F Y H:i:s') : trans('Unscheduled'),
            'status' => $this->resource->status->label,
            'action' => view('components.datatables.button-group', compact('elements'))->render(),
        ];
    }
}
