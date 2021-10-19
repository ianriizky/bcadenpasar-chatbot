<?php

namespace App\Models;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Infrastructure\Database\Eloquent\Model;
use App\Models\Contracts\MorphToIssuerable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends Model implements MorphToIssuerable
{
    use HasFactory,
        Concerns\OrderStatus\Attribute,
        Concerns\OrderStatus\Relation;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'status',
        'note',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'status' => EnumOrderStatus::class,
    ];

    /**
     * Determine whether the current status is not able to create order schedule.
     *
     * @return bool
     */
    public function cantCreateSchedule(): bool
    {
        return EnumOrderStatus::draft()->equals($this->status);
    }

    /**
     * Determine whether the current status is able to create order schedule.
     *
     * @return bool
     */
    public function canCreateSchedule(): bool
    {
        return EnumOrderStatus::on_progress()->equals($this->status);
    }

    /**
     * Determine whether the order has been scheduled.
     *
     * @return bool
     */
    public function hasBeenScheduled(): bool
    {
        return
            EnumOrderStatus::scheduled()->equals($this->status) ||
            EnumOrderStatus::rescheduled()->equals($this->status);
    }

    /**
     * Determine whether the order has been finished.
     *
     * @return bool
     */
    public function hasBeenFinished(): bool
    {
        return EnumOrderStatus::finished()->equals($this->status);
    }

    /**
     * Determine whether the order has been canceled.
     *
     * @return bool
     */
    public function hasBeenCanceled(): bool
    {
        return EnumOrderStatus::canceled()->equals($this->status);
    }
}
