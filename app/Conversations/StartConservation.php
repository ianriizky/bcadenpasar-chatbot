<?php

namespace App\Conversations;

use App\Enum\Gender;
use App\Models\Customer;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Str;

class StartConservation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return void
     */
    public function run()
    {
        if ($customer = Customer::retrieveByBotManUser($this->getUser())) {
            $this->setUserStorage(['gender' => $customer->gender]);
        }

        $name = Str::ucfirst($this->getUser()->getFirstName());
        $title = $this->getTitle($this->getUserStorage('gender'));

        $this->sayRenderable('conversations.start.greeting', viewData: compact('name', 'title'));

        if ($customer) {
            return $this->startConversation(new HomeConservation);
        }

        $response = $this->reply(
            $question = view('conversations.start.confirm-gender')->render(),
            $additionalParameters = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()->addRow(
                KeyboardButton::create(view('conversations.start.reply-gender-male')->render())->callbackData('male'),
                KeyboardButton::create(view('conversations.start.reply-gender-female')->render())->callbackData('female')
            )->addRow(
                KeyboardButton::create(view('conversations.start.reply-gender-undefined')->render())->callbackData('unknown')
            )->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($name, $response) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            $this->setUserStorage(['gender' => $gender = $answer->getValue()]);

            $title = $this->getTitle($gender);

            $this->deleteTelegramMessageFromResponse($response);

            return $this
                ->sayRenderable('conversations.start.thankyou', compact('name', 'title'))
                ->startConversation(new HomeConservation);
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Return specified title based on the given value.
     *
     * @param  string|int  $value
     * @param  string  $default
     * @return string
     */
    protected function getTitle($value, string $default = 'Bapak/Ibu'): string
    {
        return Gender::title($value) ?? $default;
    }
}
