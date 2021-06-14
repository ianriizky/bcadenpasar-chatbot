<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StartConservation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        $name = Str::ucfirst($this->getBot()->getUser()->getFirstName());

        return $this
            ->sayRenderable('conversations.start.greeting', viewData: compact('name'))
            ->askRenderable('conversations.start.ask-gender', viewData: compact('name'), next: function (Answer $answer) {
                if (!$answer->isInteractiveMessageReply()) {
                    return;
                }

                $this->setUserStorage(['gender' => $answer->getValue()]);

                return $this->getBot()->startConversation(new HomeConservation);
            }, additionalParameters: array_merge(Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()->addRow(
                KeyboardButton::create(view('conversations.start.reply-gender-male')->render())->callbackData('male'),
                KeyboardButton::create(view('conversations.start.reply-gender-female')->render())->callbackData('female')
            )->addRow(
                KeyboardButton::create(view('conversations.start.reply-gender-undefined')->render())->callbackData('unknown')
            )->toArray(), [
                'remove_keyboard' => true,
            ])
        );
    }
}
