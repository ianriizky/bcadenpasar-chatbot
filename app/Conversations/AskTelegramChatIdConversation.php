<?php

namespace App\Conversations;

class AskTelegramChatIdConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this->say('Berikut ini adalah chat ID anda: ' . $this->getUser()->getId());
    }
}
