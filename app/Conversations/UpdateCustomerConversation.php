<?php

namespace App\Conversations;

use App\Http\Requests\Customer\UpdateRequest as CustomerUpdateRequest;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class UpdateCustomerConversation extends RegisterCustomerConversation
{
    /**
     * {@inheritDoc}
     */
    public function run()
    {
        return $this->askGender();
    }

    /**
     * Ask the customer gender.
     *
     * @return $this
     */
    protected function askGender()
    {
        $response = $this->reply(
            $question = view('conversations.update-customer.confirm-gender')->render(),
            $additionalParameters = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()->addRow(
                KeyboardButton::create(view('conversations.start.reply-gender-male')->render())->callbackData('male'),
                KeyboardButton::create(view('conversations.start.reply-gender-female')->render())->callbackData('female')
            )->addRow(
                KeyboardButton::create(view('conversations.start.reply-gender-undefined')->render())->callbackData('unknown')
            )->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            $this->setUserStorage(['gender' => $answer->getValue()]);

            $this->deleteTelegramMessageFromResponse($response);

            return $this->askEmail();
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * {@inheritDoc}
     */
    protected function askEmail(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.exchange.ask-email', function (Answer $answer) {
            $value = $answer->getText();
            $validator = CustomerUpdateRequest::createValidator($value, 'email');

            if ($validator->fails()) {
                return $this->askEmail($validator->errors()->first('email'));
            }

            $this->setUserStorage(['email' => $validator->validated()['email']]);

            return $this->askFullName();
        });
    }

    /**
     * {@inheritDoc}
     */
    protected function askPhone(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.register-customer.ask-phone', function () {
            $value = $this->getMessagePayload('contact.phone_number');
            $validator = CustomerUpdateRequest::createValidator($value, 'phone');

            if ($validator->fails()) {
                return $this->askPhone($validator->errors()->first('phone'));
            }

            $this->setUserStorage(['phone' => $validator->validated()['phone']]);

            return $this->askWhatsappPhone();
        }, additionalParameters: ['reply_markup' => json_encode([
            'keyboard' => [[['text' => '☎️ ' . trans('Send My Phone Number'), 'request_contact' => true]]],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
            'remove_keyboard' => true,
        ])]);
    }
}
