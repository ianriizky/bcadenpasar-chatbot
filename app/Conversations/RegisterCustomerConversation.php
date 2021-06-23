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
     * @return $this
     */
    protected function askIdentityNumberOption()
    {
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
                return $this->displayFallback($answer->getText());
            }

            return $value === 'yes'
                ? $this->askAccountNumber()
                : $this->askIdentityCard();
        });
    }

    /**
     * Ask customer account number.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askAccountNumber(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.register-customer.ask-account_number', function (Answer $answer) {
            if ($answer->getText() === '⏪ Kembali ke menu opsi rekening/KTP') {
                return $this->askIdentityNumberOption();
            }

            $value = $answer->getText();
            $validator = CustomerStoreRequest::createValidator($value, 'account_number');

            if ($validator->fails()) {
                return $this->askAccountNumber($validator->errors()->first('account_number'));
            }

            $this->setUserStorage(['account_number' => $validator->validated()['account_number']]);

            return $this->askPhone();
        }, additionalParameters: Keyboard::create(Keyboard::TYPE_KEYBOARD)->resizeKeyboard()->oneTimeKeyboard()->addRow(
            KeyboardButton::create('⏪ Kembali ke menu opsi rekening/KTP')
        )->toArray());
    }

    /**
     * Ask customer identity card number and image.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askIdentityCard(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.register-customer.ask-identitycard_number', function (Answer $answer) {
            if ($answer->getText() === '⏪ Kembali ke menu opsi rekening/KTP') {
                return $this->askIdentityNumberOption();
            }

            $value = $answer->getText();
            $validator = CustomerStoreRequest::createValidator($value, 'identitycard_number');

            if ($validator->fails()) {
                return $this->askIdentityCard($validator->errors()->first('identitycard_number'));
            }

            $this->setUserStorage(['identitycard_number' => $validator->validated()['identitycard_number']]);

            return $this->askRenderable('conversations.register-customer.ask-identitycard_image', function (Answer $answer) {
                if ($answer->getText() === '⏪ Kembali ke menu opsi rekening/KTP') {
                    return $this->askIdentityNumberOption();
                }

                $photos = $this->getMessagePayload('photo', []);

                if (empty($photos)) {
                    return $this->askIdentityCard('❌ Foto KTP ' . trans('could not be found.'));
                }

                $this->say(sprintf('⏳ <em>%s</em>', trans('Please wait')));

                if (!$filename = download_telegram_photo($this->getMessagePayload('photo'), Customer::IDENTITYCARD_IMAGE_PATH)) {
                    return $this->askIdentityCard('❌ Foto KTP ' . trans('could not be saved.'));
                }

                $this->setUserStorage(['identitycard_image' => $filename]);

                return $this
                    ->say('✅ ' . trans(':action ran successfully!', ['action' => 'Upload foto KTP']))
                    ->askPhone();
            });
        }, additionalParameters: Keyboard::create(Keyboard::TYPE_KEYBOARD)->resizeKeyboard()->oneTimeKeyboard()->addRow(
            KeyboardButton::create('⏪ Kembali ke menu opsi rekening/KTP')
        )->toArray());
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
                return $this->displayFallback($answer->getText());
            }

            $phone = $this->getUserStorage('phone');

            if ($value === 'no') {
                $callback = function (string $validationErrorMessage = null) use (&$callback) {
                    $this->displayValidationErrorMessage($validationErrorMessage);

                    $this->askRenderable('conversations.register-customer.ask-whatsapp_phone', function (Answer $answer) use (&$phone, $callback) {
                        $value = $answer->getText();
                        $validator = CustomerStoreRequest::createValidator($value, 'whatsapp_phone');

                        if ($validator->fails()) {
                            return $callback($validator->errors()->first('whatsapp_phone'));
                        }

                        $phone = $validator->validated()['whatsapp_phone'];
                    });
                };

                return value($callback);
            }

            $this->setUserStorage(['whatsapp_phone' => $phone]);

            return $this->askLocation();
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
            $this->setUserStorage([
                'location_latitude' => $this->getMessagePayload('location.latitude'),
                'location_longitude' => $this->getMessagePayload('location.longitude'),
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
     * @return $this
     */
    protected function askDataConfirmation()
    {
        $user = $this->getUser();
        $userStorage = $this->getUserStorage();

        $question = Question::create(view('conversations.register-customer.confirm-customer_data', compact('user', 'userStorage'))->render())
            ->callbackId('register_confirm_customer_data')
            ->addButtons([
                Button::create(view('conversations.register-customer.reply-customer_data-yes')->render())->value('yes'),
                Button::create(view('conversations.register-customer.reply-customer_data-no')->render())->value('no'),
            ]);

        return $this->sayRenderable('conversations.register-customer.thankyou', additionalParameters: ['reply_markup' => json_encode([
            'remove_keyboard' => true,
        ])])->ask($question, function (Answer $answer) use ($user, $userStorage) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!in_array($value = $answer->getValue(), ['yes', 'no'])) {
                return $this->displayFallback($answer->getText());
            }

            if ($value === 'no') {
                $this->destroyUserStorage();

                return $this->run();
            }

            Customer::updateOrCreateByBotManUser($user, $userStorage);
            $this->destroyUserStorage();

            return $this->startPreviousConversation();
        });
    }
}
