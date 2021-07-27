<?php

namespace App\Support\Events;

use App\Models\Contracts\HasTelegramChatId;
use App\Models\User;
use InvalidArgumentException;

trait HandleRecipient
{
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
}
