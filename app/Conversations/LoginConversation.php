<?php

namespace App\Conversations;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Support\Auth\MultipleIdentifier;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class LoginConversation extends Conversation
{
    use MultipleIdentifier;

    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this->askIdentifier();
    }

    /**
     * Ask credential identifier.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askIdentifier(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.login.ask-identifier', function (Answer $answer) {
            if (trim($answer->getText()) === $this->cancelText()) {
                return $this->cancelLogin();
            }

            $value = $answer->getText();
            $validator = LoginRequest::createValidator($value, 'identifier');

            if ($validator->fails()) {
                return $this->askIdentifier($validator->errors()->first('identifier'));
            }

            $this->setUserStorage(['credential_identifier' => $validator->validated()['identifier']]);

            return $this->askPassword();
        }, additionalParameters: Keyboard::create(Keyboard::TYPE_KEYBOARD)->addRow(
            KeyboardButton::create($this->cancelText())
        )->toArray());
    }

    /**
     * Ask credential password.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askPassword(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.login.ask-password', function (Answer $answer) {
            if (trim($answer->getText()) === $this->cancelText()) {
                return $this->cancelLogin();
            }

            $value = $answer->getText();
            $validator = LoginRequest::createValidator($value, 'password');

            if ($validator->fails()) {
                return $this->askPassword($validator->errors()->first('password'));
            }

            $this->setUserStorage(['credential_password' => $validator->validated()['password']]);

            return $this->runLogin();
        });
    }

    /**
     * Run login process.
     *
     * @return $this
     */
    protected function runLogin()
    {
        $value = $this->getUserStorage('credential_identifier');

        $isValid = Auth::validate([
            static::getIdentifierField($value) => $value,
            'password' => $this->getUserStorage('credential_password'),
        ]);

        if (!$isValid) {
            return $this->askIdentifier(trans('auth.failed'));
        }

        $count = User::query()->where(static::getIdentifierField($value), $value)->update([
            'telegram_chat_id' => $this->getBot()->getUser()->getId(),
        ]);

        if (empty($count)) {
            return $this->askIdentifier(trans('auth.failed'));
        }

        return $this->sayRenderable('conversations.login.alert-success', additionalParameters: ['reply_markup' => json_encode([
            'remove_keyboard' => true,
        ])]);
    }

    /**
     * Return cancel login text.
     *
     * @return string
     */
    protected function cancelText(): string
    {
        return trim(view('conversations.login.cancel')->render());
    }

    /**
     * Cancel login process.
     *
     * @return $this
     */
    protected function cancelLogin()
    {
        return $this->sayRenderable('conversations.login.alert-cancel', additionalParameters: ['reply_markup' => json_encode([
            'remove_keyboard' => true,
        ])]);
    }
}
