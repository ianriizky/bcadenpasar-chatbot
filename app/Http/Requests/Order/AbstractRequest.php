<?php

namespace App\Http\Requests\Order;

use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Denomination;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getAttributes()
    {
        $attributes = [
            'customer_id' => trans('admin-lang.customer'),
            'user_id' => trans('admin-lang.user'),
            'branch_id' => trans('admin-lang.branch'),
            'code' => trans('Code'),
            'schedule_date' => trans('Schedule Date'),
            'order_status.status' => trans('Order Status'),
            'order_status.note' => trans('Note'),
        ];

        foreach (request()->input('items', []) as $index => $item) {
            $number = $index + 1;

            $attributes['items.' . $index . '.denomination_id'] = trans('admin-lang.denomination') . ' ' . $number;
            $attributes['items.' . $index . '.quantity_per_bundle'] = trans('Quantity Per Bundle') . ' ' . $number;
            $attributes['items.' . $index . '.bundle_quantity'] = trans('Bundle Quantity') . ' ' . $number;
        }

        return $attributes;
    }

    /**
     * Return new order model instance based on the validated data from request.
     *
     * @return \App\Models\Order
     */
    public function getOrder(): Order
    {
        return Order::make($this->only('schedule_date'));
    }

    /**
     * Return new order status model instance based on the validated data from request.
     *
     * @return \App\Models\OrderStatus
     */
    public function getOrderStatus(): OrderStatus
    {
        return OrderStatus::make([
            'status' => data_get($this->validated(), 'order_status.status'),
            'note' => data_get($this->validated(), 'order_status.note'),
        ])->setIssuerableRelationValue($this->getCustomer());
    }

    /**
     * Return collection of new item model based on the validated data from request.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Item>
     */
    public function getItems(): Collection
    {
        return Collection::make(data_get($this->validated(), 'items.*', []))->map(
            fn (array $item) => Item::make($item)->setDenominationRelationValue(
                Denomination::find($item['denomination_id'])
            )
        );
    }

    /**
     * Return customer model based on the request.
     *
     * @param  string  $key
     * @return \App\Models\Customer
     */
    public function getCustomer(string $key = 'customer_id'): Customer
    {
        return Customer::find($this->input($key));
    }

    /**
     * Return user model based on the request.
     *
     * @param  string  $key
     * @return \App\Models\User|null
     */
    public function getUser(string $key = 'user_id'): ?User
    {
        return User::find($this->input($key));
    }

    /**
     * Return branch model based on the request.
     *
     * @param  string  $key
     * @return \App\Models\Branch|null
     */
    public function getBranch(string $key = 'branch_id'): ?Branch
    {
        return Branch::find($this->input($key));
    }
}
