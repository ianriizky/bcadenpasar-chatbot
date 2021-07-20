<?php

namespace App\Conversations;

use Illuminate\Support\Arr;

class HelpConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        $conversations = HomeConservation::conversations()->mapWithKeys(fn ($conversation) =>
            [Arr::first(Arr::wrap($conversation['command'])) => $conversation['description']]
        );

        return $this
            ->sayRenderable('conversations.help.index')
            ->sayRenderable('conversations.help.command-list', viewData: compact('conversations'));
    }
}
