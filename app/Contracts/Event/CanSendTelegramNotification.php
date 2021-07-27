<?php

namespace App\Contracts\Event;

interface CanSendTelegramNotification
{
    /**
     * Return telegram chat id value.
     *
     * @return string|array
     */
    public function getTelegramChatId();

    /**
     * Return telegram chat message content.
     *
     * @return string
     */
    public function getTelegramMessage(): string;
}
