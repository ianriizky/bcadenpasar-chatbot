<?php

namespace App\Events;

use App\Contracts\Event\CanSendTelegramNotification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements CanSendTelegramNotification, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithQueue;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(protected Order $order)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getTelegramChatId(): array
    {
        return User::getAdmin(['id', 'telegram_chat_id'])->pluck('telegram_chat_id')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function getTelegramMessage(): string
    {
        return view('notifications.order-created', [
            'order' => $this->order,
        ])->render();
    }
}
