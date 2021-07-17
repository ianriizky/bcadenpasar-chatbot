<?php

namespace App\Conversations;

use App\Http\Requests\Customer\StoreRequest as CustomerStoreRequest;
use App\Models\Customer;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class RegisterCustomerConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this->askEmail();
    }

    /**
     * Ask customer email.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askEmail(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.exchange.ask-email', function (Answer $answer) {
            $value = $answer->getText();
            $validator = CustomerStoreRequest::createValidator($value, 'email');

            if ($validator->fails()) {
                return $this->askEmail($validator->errors()->first('email'));
            }

            $this->setUserStorage(['email' => $email = $validator->validated()['email']]);

            $username = $this->getUser()->getUsername();

            if ($customer = Customer::retrieveByUsernameAndEmail(compact('username', 'email'))) {
                return $this->startPreviousConversation();
            }

            return $this->askFullName();
        });
    }

    /**
     * Ask customer full name.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askFullName(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.register-customer.ask-fullname', function (Answer $answer) {
            $value = $answer->getText();
            $validator = CustomerStoreRequest::createValidator($value, 'fullname');

            if ($validator->fails()) {
                return $this->askFullName($validator->errors()->first('fullname'));
            }

            $this->setUserStorage(['fullname' => $validator->validated()['fullname']]);

            return $this->askIdentityNumberOption();
        });
    }

    /**
     * Ask if customer want to input account number or identity card number.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askIdentityNumberOption(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $this->setUserStorage([
            'account_number' => null,
            'identitycard_number' => null,
            'identitycard_image' => null,
        ]);

        $question = Question::create(view('conversations.register-customer.confirm-identity_number')->render())
            ->callbackId('register_confirm_identity_number')
            ->addButtons([
                Button::create(view('conversations.register-customer.reply-identity_number-yes')->render())->value('yes'),
                Button::create(view('conversations.register-customer.reply-identity_number-no')->render())->value('no'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!in_array($value = $answer->getValue(), ['yes', 'no'])) {
                return $this->askIdentityNumberOption($this->fallbackMessage($answer->getText()));
            }

            return $value === 'yes'
                ? $this->askAccountNumber()
                : $this->askIdentityCard();
        });
    }

    /**
     * Create text to back into identity option menu.
     *
     * @return string
     */
    protected function backToIdentityNumberOption(): string
    {
        return trim(view('components.conversations.back', ['text' => 'opsi rekening/KTP'])->render());
    }

    /**
     * Create keyboard instance to back into identity option menu.
     *
     * @return \BotMan\Drivers\Telegram\Extensions\Keyboard
     */
    protected function keyboardBackToIdentityNumberOption()
    {
        return Keyboard::create(Keyboard::TYPE_KEYBOARD)->resizeKeyboard()->oneTimeKeyboard()->addRow(
            KeyboardButton::create($this->backToIdentityNumberOption())
        );
    }

    /**
     * Ask customer account number.
     *
     * @param  string|null  $validationErrorMessage
     * @return void
     */
    protected function askAccountNumber(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $response = $this->reply(
            $question = view('conversations.register-customer.ask-account_number')->render(),
            $additionalParameters = $this->keyboardBackToIdentityNumberOption()->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response) {
            if (trim($answer->getText()) === $this->backToIdentityNumberOption()) {
                $this->deleteTelegramMessageFromResponse($response);

                return $this->askIdentityNumberOption();
            }

            $value = $answer->getText();
            $validator = CustomerStoreRequest::createValidator($value, 'account_number');

            if ($validator->fails()) {
                return $this->askAccountNumber($validator->errors()->first('account_number'));
            }

            $this->setUserStorage(['account_number' => $validator->validated()['account_number']]);

            return $this->askPhone();
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Ask customer identity card number and image.
     *
     * @param  string|null  $validationErrorMessage
     * @return void
     */
    protected function askIdentityCard(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $response1 = $this->reply(
            $question = view('conversations.register-customer.ask-identitycard_number')->render(),
            $additionalParameters = $this->keyboardBackToIdentityNumberOption()->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response1) {
            if (trim($answer->getText()) === $this->backToIdentityNumberOption()) {
                $this->deleteTelegramMessageFromResponse($response1);

                return $this->askIdentityNumberOption();
            }

            $value = $answer->getText();
            $validator = CustomerStoreRequest::createValidator($value, 'identitycard_number');

            if ($validator->fails()) {
                return $this->askIdentityCard($validator->errors()->first('identitycard_number'));
            }

            $this->setUserStorage(['identitycard_number' => $validator->validated()['identitycard_number']]);

            $response2 = $this->reply(
                $question = view('conversations.register-customer.ask-identitycard_image')->render()
            );

            return $this->getBot()->storeConversation($this, function (Answer $answer) use ($response1, $response2) {
                if (trim($answer->getText()) === $this->backToIdentityNumberOption()) {
                    $this->deleteTelegramMessageFromResponse($response1);
                    $this->deleteTelegramMessageFromResponse($response2);

                    return $this->askIdentityNumberOption();
                }

                $photos = $this->getMessagePayload('photo', []);

                if (empty($photos)) {
                    return $this->askIdentityCard('❌ Foto KTP ' . trans('could not be found.'));
                }

                $response = $this->reply(view('components.conversations.please-wait')->render());

                $filename = download_telegram_photo($this->getMessagePayload('photo'), Customer::IDENTITYCARD_IMAGE_PATH);

                $this->deleteTelegramMessageFromResponse($response);

                if (!$filename) {
                    return $this->askIdentityCard('❌ Foto KTP ' . trans('could not be saved.'));
                }

                $this->setUserStorage(['identitycard_image' => $filename]);

                return $this
                    ->say('✅ ' . trans(':action ran successfully!', ['action' => 'Upload foto KTP']))
                    ->askPhone();
            }, $question);
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Ask customer phone number.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askPhone(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.register-customer.ask-phone', function () {
            $value = $this->getMessagePayload('contact.phone_number');
            $validator = CustomerStoreRequest::createValidator($value, 'phone');

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

    /**
     * Ask customer whatsapp phone.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askWhatsappPhone(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $question = Question::create(view('conversations.register-customer.confirm-whatsapp_phone', ['phone' => $this->getUserStorage('phone')])->render())
            ->callbackId('register_confirm_whatsapp_phone')
            ->addButtons([
                Button::create(view('conversations.register-customer.reply-whatsapp_phone-yes')->render())->value('yes'),
                Button::create(view('conversations.register-customer.reply-whatsapp_phone-no')->render())->value('no'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!in_array($value = $answer->getValue(), ['yes', 'no'])) {
                return $this->askWhatsappPhone($this->fallbackMessage($answer->getText()));
            }

            $phone = $this->getUserStorage('phone');

            if ($value === 'yes') {
                $this->setUserStorage(['whatsapp_phone' => $phone]);

                return $this->askLocation();
            }

            return value(function (string $validationErrorMessage = null) use (&$callback) {
                $this->displayValidationErrorMessage($validationErrorMessage);

                $this->askRenderable('conversations.register-customer.ask-whatsapp_phone', function (Answer $answer) use (&$phone, $callback) {
                    $value = $answer->getText();
                    $validator = CustomerStoreRequest::createValidator($value, 'whatsapp_phone');

                    if ($validator->fails()) {
                        return $callback($validator->errors()->first('whatsapp_phone'));
                    }

                    $phone = $validator->validated()['whatsapp_phone'];

                    $this->setUserStorage(['whatsapp_phone' => $phone]);

                    return $this->askLocation();
                });
            });
        });
    }

    /**
     * Ask customer location data.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askLocation(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.register-customer.ask-location', function () {
            $latitude = $this->getMessagePayload('location.latitude');
            $longitude = $this->getMessagePayload('location.longitude');

            $validatorLatitude = CustomerStoreRequest::createValidator($latitude, 'location_latitude');
            $validatorLongitude = CustomerStoreRequest::createValidator($longitude, 'location_longitude');

            if ($validatorLatitude->fails()) {
                return $this->askLocation($validatorLatitude->errors()->first('location_latitude'));
            }

            if ($validatorLongitude->fails()) {
                return $this->askLocation($validatorLongitude->errors()->first('location_latitude'));
            }

            $this->setUserStorage([
                'location_latitude' => $latitude,
                'location_longitude' => $longitude,
            ]);

            return $this->askDataConfirmation();
        }, additionalParameters: ['reply_markup' => json_encode([
            'keyboard' => [[['text' => '📍 ' . trans('Send My Location'), 'request_location' => true]]],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ])]);
    }

    /**
     * Ask if the inputed data from customer is correct or not.
     *
     * @param  string|null  $validationErrorMessage
     * @return void
     */
    protected function askDataConfirmation(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $this->sayRenderable('conversations.register-customer.thankyou', additionalParameters: ['reply_markup' => json_encode([
            'remove_keyboard' => true,
        ])]);

        $user = $this->getUser();
        $userStorage = $this->getUserStorage();

        $response = $this->reply(
            $question = view('conversations.register-customer.confirm-customer_data', compact('user', 'userStorage'))->render(),
            $additionalParameters = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()->addRow(
                KeyboardButton::create(view('conversations.register-customer.reply-customer_data-yes')->render())->callbackData('yes')
            )->addRow(
                KeyboardButton::create(view('conversations.register-customer.reply-customer_data-no')->render())->callbackData('no')
            )->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($user, $userStorage, $response) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!in_array($value = $answer->getValue(), ['yes', 'no'])) {
                return $this->askDataConfirmation($this->fallbackMessage($answer->getText()));
            }

            $this->deleteTelegramMessageFromResponse($response);

            if ($value === 'no') {
                $this->destroyUserStorage();

                return $this->run();
            }

            Customer::updateOrCreateByBotManUser($user, $userStorage);

            $this->destroyUserStorage();

            return $this->startPreviousConversation();
        }, question: $question, additionalParameters: $additionalParameters);
    }
}
