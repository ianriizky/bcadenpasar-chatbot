<?php

namespace App\Conversations;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Order;
use App\Models\User;
use App\Support\Auth\MultipleIdentifier;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class CheckOrderStatusConversation extends Conversation
{
    use MultipleIdentifier;

    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this->askCode();
    }

    /**
     * Ask order code.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function askCode(string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        return $this->askRenderable('conversations.check-order-status.ask-code', function (Answer $answer) {
            if (trim($answer->getText()) === $this->cancelText()) {
                return $this->cancelCheck();
            }

            if (!$order = Order::firstWhere('code', $answer->getText())) {
                return $this->askCode(trans('No Results Found.'));
            }

            $order->load('customer:id,fullname', 'user:id,fullname', 'branch', 'items.denomination');

            return $this->sayRenderable('conversations.check-order-status.alert-order', compact('order'));
        }, additionalParameters: Keyboard::create(Keyboard::TYPE_KEYBOARD)->addRow(
            KeyboardButton::create($this->cancelText())
        )->toArray());
    }

    /**
     * Return cancel login text.
     *
     * @return string
     */
    protected function cancelText(): string
    {
        return trim(view('conversations.check-order-status.cancel')->render());
    }

    /**
     * Cancel check order code process.
     *
     * @return $this
     */
    protected function cancelCheck()
    {
        return $this->sayRenderable('conversations.check-order-status.alert-cancel', additionalParameters: ['reply_markup' => json_encode([
            'remove_keyboard' => true,
        ])]);
    }
}
