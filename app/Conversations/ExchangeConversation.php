<?php

namespace App\Conversations;

use App\Models\Customer;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class ExchangeConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return $this
     */
    public function run()
    {
        return $this
            ->sayRenderable('conversations.exchange.index')
            ->verifyCustomer();
    }

    /**
     * Verify if the current customer has been registered or not.
     *
     * @return $this
     */
    protected function verifyCustomer()
    {
        if (!$customer = Customer::retrieveByBotManUser($this->getUser())) {
            $username = $this->getUser()->getUsername();
            $email = $this->getUserStorage('email');

            if (!$customer = Customer::retrieveByUsernameAndEmail(compact('username', 'email'))) {
                return $this
                    ->setPreviousConversation($this)
                    ->say('⚠️ Mohon lakukan registrasi terlebih dulu sebelum melakukan transaksi penukaran uang')
                    ->startConversation(new RegisterCustomerConversation);
            }
        }

        return $this->displayCustomerData($customer);
    }

    /**
     * Reply with customer data.
     *
     * @param  \App\Models\Customer  $customer
     * @return $this
     */
    protected function displayCustomerData(Customer $customer)
    {
        if (!$this->getPreviousConversation() instanceof static) {
            $this->say('<em>Data anda sebelumnya sudah pernah terekam di database kami.</em>');
        }

        $this->destroyUserStorage(forceDestroy: true);

        $question = Question::create(view('conversations.exchange.confirm-customer_data', compact('customer'))->render())
            ->callbackId('exchange_confirm_customer_data')
            ->addButtons([
                Button::create(view('conversations.register-customer.reply-customer_data-yes')->render())->value('yes'),
                Button::create(view('conversations.register-customer.reply-customer_data-no')->render())->value('no'),
            ]);

        return $this->ask($question, next: function (Answer $answer) use ($customer) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!in_array($value = $answer->getValue(), ['yes', 'no'])) {
                return $this->displayFallback($answer->getText());
            }

            if ($value === 'no') {
                $this->destroyUserStorage();

                return $this
                    ->setPreviousConversation($this)
                    ->startConversation(new UpdateCustomerConversation);
            }

            return $this->recordOrder($customer);
        });
    }

    /**
     * Record customer order data.
     *
     * @param  \App\Models\Customer  $customer
     * @return $this
     */
    protected function recordOrder(Customer $customer)
    {
        return $this->say('terima kasih :)');

        $response = $this->reply(
            $question = view('conversations.register-customer.confirm-customer_data', compact('user', 'userStorage'))->render(),
            $additionalParameters = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()->addRow(
                KeyboardButton::create(view('conversations.register-customer.reply-customer_data-yes')->render())->callbackData('yes')
            )->addRow(
                KeyboardButton::create(view('conversations.register-customer.reply-customer_data-no')->render())->callbackData('no')
            )->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }
        }, question: $question, additionalParameters: $additionalParameters);
    }
}
