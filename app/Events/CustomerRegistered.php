<?php

namespace App\Events;

use App\Contracts\Event\CanSendTelegramNotification;
use App\Models\Contracts\HasTelegramChatId;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class CustomerRegistered implements CanSendTelegramNotification, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithQueue;

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
     * Find the recipient from user model if the given parameter is null.
     *
     * @param  \App\Models\Contracts\HasTelegramChatId|null  $recipient
     * @return \App\Models\Contracts\HasTelegramChatId
     *
     * @throws \InvalidArgumentException
     */
    protected function findRecipientFromUser(?HasTelegramChatId $recipient): HasTelegramChatId
    {
        if ($recipient instanceof HasTelegramChatId) {
            return $recipient;
        }

        if (!$recipient = User::findAdmin()) {
            throw new InvalidArgumentException(
                sprintf('Recipient for event %s is unavailable', static::class)
            );

        }

        return $recipient;
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
