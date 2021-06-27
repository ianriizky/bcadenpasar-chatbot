<?php

namespace App\Conversations;

class HelpConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this
            ->sayRenderable('conversations.help.index')
            ->sayRenderable('conversations.help.command-list');
    }
}
