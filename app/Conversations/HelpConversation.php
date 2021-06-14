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
            ->sayRenderable(view('conversations.help.index'))
            ->sayRenderable(view('conversations.help.command-list'));
    }
}
