<?php

namespace App\Http\Resources\DataTables;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Denomination $resource
 */
class DenominationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $typeBadge = sprintf(<<<'html'
            <span class="badge badge-%s">
                <i class="fa fa-%s"></i> %s
            </span>
        html,
            $this->resource->type->isCoin() ? 'danger' : 'success',
            $this->resource->type->isCoin() ? 'coins' : 'money-bill',
            $this->resource->type->label
        );

        return [
            'checkbox' => view('components.datatables.checkbox', [
                'value' => $this->resource->getKey(),
            ])->render(),
            'name' => $this->resource->name,
            'value' => $this->resource->value,
            'type' => $typeBadge,
            'quantity_per_bundle' => $this->resource->quantity_per_bundle,
            'action' => view('components.datatables.button-group', [
                'elements' => [
                    view('components.datatables.link-show', [
                        'url' => route('admin.denomination.edit', $this->resource),
                    ])->render(),
                    view('components.datatables.link-destroy', [
                        'url' => route('admin.denomination.destroy', $this->resource),
                    ])->render(),
                ],
            ])->render(),
        ];
    }
}
