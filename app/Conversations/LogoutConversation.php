<?php

namespace App\Conversations;

use App\Models\User;
use App\Support\Auth\MultipleIdentifier;

class LogoutConversation extends Conversation
{
    use MultipleIdentifier;

    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        /** @var \App\Models\User|null $user */
        if ($user = User::firstWhere('telegram_chat_id', $this->getUser()->getId())) {
            $user->update(['telegram_chat_id' => null]);

            return $this->sayRenderable('conversations.logout.alert-success');
        }

        return $this->sayRenderable('conversations.logout.alert-unauthenticated');
    }
}
