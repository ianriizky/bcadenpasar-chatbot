<?php

namespace App\Conversations;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Customer;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ExchangeConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this->askFullName();
    }

    /**
     * Ask customer full name.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askFullName(string $validationErrorMessage = null)
    {
        if ($validationErrorMessage) {
            $this->say($validationErrorMessage);
        }

        return $this->askRenderable('conversations.exchange.ask-fullname', function (Answer $answer) {
            $value = $answer->getText();
            $validator = RegisterRequest::createValidator($value, 'fullname');

            if ($validator->fails()) {
                return $this->askFullName($validator->errors()->first('username'));
            }

            $this->setUserStorage(['fullname' => $validator->validated()['fullname']]);

            return $this->askIdentityNumberOption();
        });
    }

    /**
     * Ask if customer want to input account number or identity card number.
     *
     * @return mixed
     */
    protected function askIdentityNumberOption()
    {
        $question = Question::create(view('conversations.exchange.ask-identitynumberoption')->render())
            ->callbackId('ask_identitynumber_option')
            ->addButtons([
                Button::create(view('conversations.exchange.reply-identitynumberoption-yes')->render())->value('yes'),
                Button::create(view('conversations.exchange.reply-identitynumberoption-no')->render())->value('no'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!in_array($value = $answer->getValue(), ['yes', 'no'])) {
                $text = $answer->getText();

                return $this->say(view('components.conversations.fallback', compact('text'))->render());
            }

            return $value === 'yes'
                ? $this->askAccountNumber()
                : $this->askIdentityCardNumber();
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

        return $this->askRenderable('conversations.exchange.ask-accountnumber', function (Answer $answer) {
            $value = $answer->getText();
            $validator = Customer\StoreRequest::createValidator($value, 'accountnumber');

            if ($validator->fails()) {
                return $this->askAccountNumber($validator->errors()->first('accountnumber'));
            }

            $this->setUserStorage(['customer_accountnumber' => $validator->validated()['accountnumber']]);

            return $this->askPhone();
        });
    }

    /**
     * Ask customer identity card number.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askIdentityCardNumber(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.exchange.ask-identitycardnumber', function (Answer $answer) {
            $value = $answer->getText();
            $validator = Customer\StoreRequest::createValidator($value, 'identitycardnumber');

            if ($validator->fails()) {
                return $this->askIdentityCardNumber($validator->errors()->first('identitycardnumber'));
            }

            $this->setUserStorage(['customer_identitycardnumber' => $validator->validated()['identitycardnumber']]);

            return $this->askPhone();
        });
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

        return $this->askRenderable('conversations.exchange.ask-phonenumber', function () {
            $response = json_decode($this->getBot()->getMessage()->getPayload(), true);

            $value = data_get($response, 'contact.phone_number');
            $validator = RegisterRequest::createValidator($value, 'phone');

            if ($validator->fails()) {
                return $this->askPhone($validator->errors()->first('phone'));
            }

            $this->setUserStorage(['phone' => $validator->validated()['phone']]);

            return $this->askLocation();
        }, additionalParameters: ['reply_markup' => json_encode([
            'keyboard' => [[['text' => '☎️ ' . trans('Send My Phone Number'), 'request_contact' => true]]],
            'resize_keyboard' => true,
        ])]);
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

        return $this->askRenderable('conversations.exchange.ask-location', function () {
            $response = json_decode($this->getBot()->getMessage()->getPayload(), true);

            $this->setUserStorage([
                'customer_location_latitude' => data_get($response, 'location.latitude'),
                'customer_location_longitude' => data_get($response, 'location.longitude'),
            ]);

            return $this->askDataConfirmation();
        }, additionalParameters: ['reply_markup' => json_encode([
            'keyboard' => [[['text' => '📍 ' . trans('Send My Location'), 'request_location' => true]]],
            'resize_keyboard' => true,
        ])]);
    }

    /**
     * Ask if the inputed data from customer is correct or not.
     *
     * @return $this
     */
    protected function askDataConfirmation()
    {
        $response = $this->getUserStorage()->toJson();

        $this->destroyUserStorage();

        return $this->say($response, [
            'reply_markup' => json_encode(['remove_keyboard' => true]),
        ]);
    }

    /**
     * Return reply response with the given validation error message.
     *
     * @param  string|null  $validationErrorMessage
     * @return void
     */
    protected function displayValidationErrorMessage(string $validationErrorMessage = null)
    {
        if ($validationErrorMessage) {
            $this->say('⛔️ ' . $validationErrorMessage);
        }
    }
}
