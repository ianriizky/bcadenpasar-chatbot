<?php

namespace App\Models\Contracts;

interface HasTelegramChatId
{
    /**
     * Return "telegram_chat_id" attribute value.
     *
     * @return string
     */
    public function getTelegramChatId(): string;
}
