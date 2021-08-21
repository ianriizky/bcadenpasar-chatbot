<?php

namespace App\Models;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Infrastructure\Database\Eloquent\Model;
use App\Models\OrderStatus as ModelOrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory,
        Concerns\Order\Attribute,
        Concerns\Order\Event,
        Concerns\Order\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'code',
        'schedule_date',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'schedule_date' => 'datetime',
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'status',
        'item_total_bundle_quantity',
        'item_total',
    ];

    /**
     * {@inheritDoc}
     */
    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * {@inheritDoc}
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        $relationship = $this->{Str::plural(Str::camel($childType))}();

        if ($relationship->getRelated() instanceof OrderStatus) {
            return $relationship->where('status', EnumOrderStatus::from($value))->first();
        }

        if ($relationship->getRelated() instanceof Item) {
            return $relationship->whereHas('denomination', function (Builder $query) use ($value) {
                return $query->where('value', $value);
            })->first();
        }

        return parent::resolveChildRouteBinding($childType, $value, $field);
    }

    /**
     * Generate code for this model.
     *
     * @param  string  $prefix
     * @param  \Illuminate\Support\Carbon|null  $date
     * @return string
     */
    public function generateCode(string $prefix = 'BCA', Carbon $date = null): string
    {
        $date ??= Carbon::today();

        return $prefix . '-' . $date->format('Ymd') . '-' . Str::random(5);
    }

    /**
     * Find the specified order based on the given code or create a new one.
     *
     * @param  string|null  $code
     * @param  \App\Models\Customer  $customer
     * @param  callable|null  $callable
     * @return static
     */
    public static function findOrCreateFromCode(?string $code, Customer $customer, callable $callable = null)
    {
        return static::where('code', $code)->firstOr(function () use ($customer, $callable) {
            $order = new Order;

            $order->setCustomerRelationValue($customer)->save();

            $orderStatus = new ModelOrderStatus(['status' => EnumOrderStatus::draft()]);

            $order->statuses()->save(
                $orderStatus->setIssuerableRelationValue($customer)
            );

            if ($callable) {
                $callable($order);
            }

            return $order;
        });
    }

    /**
     * Determine whether the "item_total" attribute value is exceeding the maximum total order value.
     *
     * @return bool
     */
    public function isMaximumTotalOrderExceeded(): bool
    {
        return $this->item_total > Configuration::getMaximumTotalOrderValue();
    }

    /**
     * Determine whether the order number of the day is exceeding the maximum order per day.
     *
     * @return bool
     */
    public static function isMaximumOrderPerDayExceeded(): bool
    {
        return static::whereDate('created_at', Carbon::today())->count() >= Configuration::getMaximumOrderPerDay();
    }
}
