<?php

namespace App\Conversations;

use App\Models\Customer;
use App\Models\Denomination;
use App\Models\Item;
use App\Models\Order;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        $this->displayValidationErrorMessage($validationErrorMessage);

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

            /** @var \App\Models\Denomination|null $denomination */
            if (!$denominations->contains($answer->getValue()) ||
                !$denomination = Denomination::find($answer->getValue())) {
                return $this->recordOrder($customer, $this->fallbackMessage($answer->getText()));
            }

            /** @var \App\Models\Order $order */
            $order = Order::findOrCreateFromCode($this->getUserStorage('order_code'), $customer, function (Order $order) {
                $this->setUserStorage(['order_code' => $order->code]);
            });

            $this->recordItem($customer, $order, $denomination);

            $this->deleteTelegramMessageFromResponse($response);
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Record customer item data.
     *
     * @param  \App\Models\Customer  $customer
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Denomination  $denomination
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function recordItem(Customer $customer, Order $order, Denomination $denomination, string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $keyboard = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard();
        $unit = Str::lower($denomination->type->label);

        collect($denomination->range_order_bundle)->chunk(3)->map(function (Collection $quantities) use ($keyboard, $unit) {
            $keyboard->addRow(...$quantities->map(fn ($quantity) => KeyboardButton::create(
                view('conversations.exchange.reply-bundle_quantity-quantity', compact('quantity'))->render()
            )->callbackData($quantity))->toArray());
        });

        $keyboard->addRow(
            KeyboardButton::create(
                view('components.conversations.back', ['text' => 'opsi pilih nominal uang'])->render()
            )->callbackData('back_to_denomination_option')
        );

        $response = $this->reply(
            $question = view('conversations.exchange.ask-bundle_quantity', compact('denomination'))->render(),
            $additionalParameters = $keyboard->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response, $customer, $order, $denomination) {
            $this->deleteTelegramMessageFromResponse($response);

            if ($answer->getValue() === 'back_to_denomination_option') {
                return $this->recordOrder($customer);
            }

            if (!in_array($answer->getValue(), $denomination->range_order_bundle)) {
                return $this->recordItem($customer, $order, $denomination, trans('validation.between.numeric', [
                    'attribute' => trans('Quantity Per Bundle'),
                    'min' => $denomination->minimum_order_bundle,
                    'max' => $denomination->maximum_order_bundle,
                ]));
            }

            $item = new Item([
                'quantity_per_bundle' => $denomination->quantity_per_bundle,
                'bundle_quantity' => $answer->getValue(),
            ]);

            $item->setDenominationRelationValue($denomination);

            $order->items()->save($item);

            $this->reply('thanks');
        }, question: $question, additionalParameters: $additionalParameters);
    }
}
