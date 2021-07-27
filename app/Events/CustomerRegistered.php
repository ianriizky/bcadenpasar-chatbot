<?php

namespace App\Events;

use App\Contracts\Event\CanSendTelegramNotification;
use App\Models\Contracts\HasTelegramChatId;
use App\Models\Customer;
use App\Support\Events\HandleRecipient;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CustomerRegistered implements CanSendTelegramNotification, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithQueue,
        HandleRecipient;

    /**
     * The customer instance data.
     *
     * @var \App\Models\Customer
     */
    protected Customer $customer;

    /**
     * The recipient data that will receive the notification.
     *
     * @var \App\Models\Contracts\HasTelegramChatId
     */
    protected HasTelegramChatId $recipient;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Customer  $customer
     * @param  \App\Models\Contracts\HasTelegramChatId|null  $recipient
     * @return void
     */
    public function __construct(Customer $customer, HasTelegramChatId $recipient = null)
    {
        $this->customer = $customer;
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
        return view('notifications.customer-registered', [
            'customer' => $this->customer,
        ])->render();
    }
}
