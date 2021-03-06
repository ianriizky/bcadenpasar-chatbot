<?php

namespace App\Conversations;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Events\OrderCreated;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Denomination;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus as ModelsOrderStatus;
use App\Models\User;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
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
        if (User::where('telegram_chat_id', $this->getUser()->getId())->count()) {
            return $this->say('conversations.exchange.alert-user');
        }

        if (Order::isMaximumOrderPerDayExceeded()) {
            return $this->sayRenderable('conversations.exchange.alert-exceeded-maximum-order-per-day');
        }

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

        $denominations = Denomination::whereIsVisible()->get(['id', 'code', 'name', 'value']);
        $keyboard = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard();

        foreach ($denominations as $denomination) {
            $keyboard->addRow(
                KeyboardButton::create(
                    view('conversations.exchange.reply-denomination', compact('denomination'))->render()
                )->callbackData($denomination->code)
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

            $this->deleteTelegramMessageFromResponse($response);

            /** @var \App\Models\Denomination|null $denomination */
            if (!$denominations->contains('code', $answer->getValue()) || !$denomination = Denomination::firstWhere('code', $answer->getValue())) {
                return $this->recordOrder($customer, $this->fallbackMessage($answer->getText()));
            }

            /** @var \App\Models\Order $order */
            $order = Order::findOrCreateFromCode($this->getUserStorage('order_code'), $customer, function (Order $order) {
                $this->setUserStorage(['order_code' => $order->code]);
            });

            if ($order->isMaximumTotalOrderExceeded()) {
                return $this->confirmOrder($order, 'Maaf, total pesanan anda sudah mencapai batas maksimum');
            }

            /**
             * If there is an order's item that has same denomination with the selected one,
             * then it will be deleted first before customer continue to record item.
             */
            if ($item = $order->items()->whereHasDenomination($denomination)->first('id')) {
                $item->delete();
            }

            /**
             * If customer want to add another item, we assume that
             * the customer already choose the branch.
             */
            if ($order->branch && $order->has('items')->exists()) {
                return $this->recordItem($order, $denomination);
            }

            return $this->recordBranch($order, $denomination);
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Record customer branch data.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Denomination  $denomination
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function recordBranch(Order $order, Denomination $denomination, string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $branches = Branch::all('id', 'name');
        $keyboard = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard();

        foreach ($branches as $branch) {
            $keyboard->addRow(
                KeyboardButton::create(
                    view('conversations.exchange.reply-branch', compact('branch'))->render()
                )->callbackData($branch->getKey())
            );
        }

        $keyboard->addRow(
            KeyboardButton::create(
                view('conversations.exchange.reply-branch-empty')->render()
            )->callbackData('branch_empty')
        );

        $response = $this->reply(
            $question = view('conversations.exchange.alert-branch')->render(),
            $additionalParameters = $keyboard->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response, $order, $denomination, $branches) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            $this->deleteTelegramMessageFromResponse($response);

            /** @var \App\Models\Branch|null $branch */
            if ((!$branches->contains($answer->getValue()) && $answer->getValue() !== 'branch_empty') || !$branch = Branch::find($answer->getValue())) {
                return $this->recordBranch($order, $denomination, $this->fallbackMessage($answer->getText()));
            }

            if ($answer->getValue() !== 'branch_empty') {
                $order->setBranchRelationValue($branch)->save();
            }

            return $this->recordItem($order, $denomination);
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Record customer item data.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Denomination  $denomination
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function recordItem(Order $order, Denomination $denomination, string $validationErrorMessage = null)
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
                view('conversations.exchange.reply-bundle_quantity-custom')->render()
            )->callbackData('bundle_quantity_custom')
        );

        $response = $this->reply(
            $question = view('conversations.exchange.ask-bundle_quantity', compact('denomination'))->render(),
            $additionalParameters = $keyboard->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response, $order, $denomination) {
            $this->deleteTelegramMessageFromResponse($response);

            if ($answer->getValue() === 'bundle_quantity_custom') {
                $this->deleteTelegramMessageFromResponse($response);

                $response2 = $this->reply(
                    $question2 = view('conversations.exchange.ask-bundle_quantity-custom', compact('denomination'))->render(),
                    $additionalParameters2 = Keyboard::create(Keyboard::TYPE_KEYBOARD)
                        ->resizeKeyboard()
                        ->oneTimeKeyboard()
                        ->addRow(KeyboardButton::create(
                            $backToDenominationOption = trim(view('components.conversations.back', ['text' => 'opsi nominal'])->render())
                        )->callbackData('back_to_denomination_option'))->toArray()
                );

                return $this->getBot()->storeConversation($this, function (Answer $answer) use ($response2, $order, $denomination, $backToDenominationOption) {
                    $this->deleteTelegramMessageFromResponse($response2);

                    if ($answer->getText() === $backToDenominationOption) {
                        return $this->recordItem($order, $denomination);
                    }

                    return $this->createItem($order, $denomination, $answer->getText());
                }, $question2, $additionalParameters2);
            }

            return $this->createItem($order, $denomination, $answer->getValue());
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Create an item data based on the given order, denomination, and value.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Denomination  $denomination
     * @param  int  $value
     * @return $this
     */
    protected function createItem(Order $order, Denomination $denomination, int $value)
    {
        if (!$denomination->isBetweenOrderBundle($value)) {
            return $this->recordItem($order, $denomination, trans('validation.between.numeric', [
                'attribute' => trans('Quantity Per Bundle'),
                'min' => $denomination->minimum_order_bundle,
                'max' => $denomination->maximum_order_bundle,
            ]));
        }

        $item = new Item([
            'quantity_per_bundle' => $denomination->quantity_per_bundle,
            'bundle_quantity' => $value,
        ]);

        $item->setDenominationRelationValue($denomination);

        $order->items()->save($item);

        return $this->confirmOrder($order);
    }

    /**
     * Confirm customer order data.
     *
     * @param  \App\Models\Order  $order
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function confirmOrder(Order $order, string $validationErrorMessage = null)
    {
        $this->displayValidationErrorMessage($validationErrorMessage);

        $keyboard = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard()
            ->addRow(KeyboardButton::create(view('conversations.exchange.reply-order-yes')->render())
                ->callbackData('update_order_status')
            )->addRow(KeyboardButton::create(view('conversations.exchange.reply-order-no-update-item')->render())
                ->callbackData('update_item')
            )->addRow(KeyboardButton::create(view('conversations.exchange.reply-order-no-recreate-item')->render())
                ->callbackData('recreate_item')
            );

        $order->load('customer:id,fullname', 'items.denomination');

        $responseConfirmOrder = $this->reply(view('conversations.exchange.confirm-order', compact('order'))->render());

        $response = $this->reply(
            $question = view('conversations.exchange.ask-order')->render(),
            $additionalParameters = $keyboard->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response, $responseConfirmOrder, $order) {
            $this->deleteTelegramMessageFromResponse($response);

            switch ($answer->getValue()) {
                case 'update_order_status':
                    $order->statuses()->save(ModelsOrderStatus::make([
                        'status' => EnumOrderStatus::on_progress(),
                    ])->setIssuerableRelationValue($order->getCustomerRelationValue()));

                    Event::dispatch(new OrderCreated($order));

                    return $this->sayRenderable('conversations.exchange.alert-update_order_status', compact('order'));

                case 'update_item':
                case 'recreate_item':
                    $this->deleteTelegramMessageFromResponse($responseConfirmOrder);

                    if ($answer->getValue() === 'recreate_item') {
                        $order->items->map->delete();
                    }

                    return $this->recordOrder($order->getCustomerRelationValue());

                default:
                    return $this->confirmOrder($order, $this->fallbackMessage($answer->getText()));
            }
        }, question: $question, additionalParameters: $additionalParameters);
    }
}
