<?php

namespace App\Conversations;

use App\Enum\Gender;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class HomeConservation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        $title = Str::ucfirst(Gender::title($this->getUserStorage('gender')));
        $name = Str::ucfirst($this->getUser()->getFirstName());

        $question = Question::create(view('conversations.home.confirm-menu', compact('title', 'name'))->render())
            ->callbackId('home_confirm_menu')
            ->addButtons([
                Button::create(view('conversations.home.reply-menu-exchange')->render())->value('exchange'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!$conversation = $this->getConversation($answer->getValue())) {
                return $this->displayFallback($answer->getText());
            }

            return $this->startConversation($conversation);
        });
    }

    /**
     * Return specific conversation instance based on the given name.
     *
     * @param  string  $name
     * @return \BotMan\BotMan\Messages\Conversations\Conversation|null
     */
    protected function getConversation(string $name): ?Conversation
    {
        switch ($name) {
            case 'exchange':
                return new ExchangeConversation;

            default:
                return null;
        }
    }
}
