<?php

namespace App\Conversations;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Models\Customer;
use App\Models\Denomination;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus as ModelOrderStatus;
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
                    ->sayRenderable('conversations.exchange.alert-registration-first')
                    ->startConversation(new RegisterCustomerConversation);
            }
        }

        return $this->displayCustomerData($customer);
    }

    /**
     * Reply with customer data.
     *
     * @param  \App\Models\Customer  $customer
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function displayCustomerData(Customer $customer, string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

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
                return $this->displayCustomerData($customer, $this->fallbackMessage($answer->getText()));
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
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function recordOrder(Customer $customer, string $validationErrorMessage = null)
    {
        $denominations = Denomination::all('id', 'name', 'value');
        $keyboard = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard();

        foreach ($denominations as $denomination) {
            $keyboard->addRow(
                KeyboardButton::create(
                    view('conversations.exchange.reply-denomination', compact('denomination'))->render()
                )->callbackData($denomination->getKey())
            );
        }

        $response = $this->reply(
            $question = view('conversations.exchange.alert-denomination')->render(),
            $additionalParameters = $keyboard->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response, $customer, $denominations) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            if (!$denominations->contains($answer->getValue()) ||
                !$denomination = Denomination::find($answer->getValue())) {
                return $this->recordOrder($customer, $this->fallbackMessage($answer->getText()));
            }

            /** @var \App\Models\Order $order */
            $order = Order::where('code', $this->getUserStorage('order_code'))->firstOr(function () use ($customer) {
                $order = new Order;

                $order->setCustomerRelationValue($customer)->save();

                $orderStatus = new ModelOrderStatus(['status' => EnumOrderStatus::draft()]);

                $order->statuses()->save(
                    $orderStatus->setIssuerableRelationValue($customer)
                );

                $this->setUserStorage(['order_code' => $order->code]);

                return $order;
            });

            $item = new Item([
                'quantity_per_bundle' => $denomination->quantity_per_bundle,
                'bundle_quantity' => 1,
            ]);

            $item->setDenominationRelationValue($denomination);

            $order->items()->save($item);

            $this->deleteTelegramMessageFromResponse($response);

            return $this->say('thanks :)');
        }, question: $question, additionalParameters: $additionalParameters);
    }
}
