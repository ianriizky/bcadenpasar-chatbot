<?php

namespace App\Events;

use App\Contracts\Event\CanSendTelegramNotification;
use App\Models\Contracts\HasTelegramChatId;
use App\Models\Order;
use App\Support\Events\HandleRecipient;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements CanSendTelegramNotification, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithQueue,
        HandleRecipient;

    /**
     * The order instance data.
     *
     * @var \App\Models\Order
     */
    protected Order $order;

    /**
     * The recipient data that will receive the notification.
     *
     * @var \App\Models\Contracts\HasTelegramChatId
     */
    protected HasTelegramChatId $recipient;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Contracts\HasTelegramChatId|null  $recipient
     * @return void
     */
    public function __construct(Order $order, HasTelegramChatId $recipient = null)
    {
        $this->order = $order;
        $this->recipient = $this->findRecipientFromUser($recipient);
    }

    /**
     * Return telegram chat id value.
     *
     * @return string|array
     */
    public function getTelegramChatId()
    {
        return $this->recipient->getTelegramChatId();
    }

    /**
     * Return telegram chat message content.
     *
     * @return string
     */
    public function getTelegramMessage(): string
    {
        return view('notifications.order-created', [
            'order' => $this->order,
        ])->render();
    }
}
