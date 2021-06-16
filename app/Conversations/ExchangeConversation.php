<?php

namespace App\Conversations;

use App\Http\Requests\Customer\StoreRequest as CustomerStoreRequest;
use App\Models\Customer;
use BotMan\BotMan\Messages\Incoming\Answer;

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
            return $this->askEmail();
        }

        return $this->displayCustomerData($customer);
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

            if (!$customer = Customer::retrieveByUsernameAndEmail(compact('username', 'email'))) {
                return $this
                    ->setPreviousConversation($this)
                    ->startConversation(new RegisterCustomerConversation);
            }

            return $this->displayCustomerData($customer);
        });
    }

    /**
     * Reply with customer data.
     *
     * @param  \App\Models\Customer  $customer
     * @return $this
     */
    protected function displayCustomerData(Customer $customer)
    {
        return $this->say($customer->toJson());
    }
}
