<?php

namespace App\Http\Requests\Order;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Infrastructure\Foundation\Http\FormRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus as ModelsOrderStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public static function getAttributes()
    {
        return [
            'customer_id' => trans('admin-lang.customer'),
            'user_id' => trans('admin-lang.user'),
            'branch_id' => trans('admin-lang.branch'),
            'code' => trans('Code'),
            'schedule_date' => trans('Schedule Date'),
            'order_status.status' => trans('Order Status'),
            'order_status.note' => trans('Note'),
        ];
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
    public function getOrderStatus(): ModelsOrderStatus
    {
        return ModelsOrderStatus::make([
            'status' => data_get($this->validated(), 'order_status.status', EnumOrderStatus::on_progress()),
            'note' => data_get($this->validated(), 'order_status.note'),
        ])->setIssuerableRelationValue(Auth::user());
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
