<?php

namespace App\Conversations;

use App\Enum\Gender;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
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

        $response = $this->reply(
            $question = view('conversations.home.confirm-menu', compact('title', 'name'))->render(),
            $additionalParameters = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()->addRow(
                KeyboardButton::create(view('conversations.home.reply-menu-exchange')->render())->callbackData('exchange')
            )->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            $this->deleteTelegramMessageFromResponse($response);

            if (!$conversation = $this->getConversation($answer->getValue())) {
                return $this->sayFallbackMessage($answer->getText());
            }

            return $this->startConversation($conversation);
        }, question: $question, additionalParameters: $additionalParameters);
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
