<?php

namespace App\Conversations;

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
        $title = Str::ucfirst($this->getTitle($this->getUserStorage('gender')));
        $name = Str::ucfirst($this->getUser()->getFirstName());

        $question = Question::create(view('conversations.home.confirm-menu', compact('title', 'name'))->render())
            ->callbackId('ask_menu')
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

            return $this->getBot()->startConversation($conversation);
        });
    }

    /**
     * Return specific title based on the given gender.
     *
     * @param  string  $gender
     * @return string|null
     */
    protected function getTitle(string $gender): ?string
    {
        switch ($gender) {
            case 'male':
                return __('Mr.');

            case 'female':
                return __('Mrs.');
        }
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
