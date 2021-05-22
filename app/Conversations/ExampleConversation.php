<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Http;

class ExampleConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * {@inheritDoc}
     */
    public function run()
    {
        return $this->askReason();
    }

    /**
     * Run ask reason process.
     *
     * @return $this
     */
    public function askReason()
    {
        $question = Question::create("Huh - you woke me up. What do you need?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Tell a joke')->value('joke'),
                Button::create('Give me a fancy quote')->value('quote'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            $response = $answer->getValue() === 'joke'
                ? Http::get('http://api.icndb.com/jokes/random')->json('value.joke')
                : Inspiring::quote();

            $this->say($response);
        });
    }
}
