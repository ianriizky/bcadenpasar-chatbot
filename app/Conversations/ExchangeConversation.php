<?php

namespace App\Conversations;

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
        return $this->askFullName();
    }

    /**
     * Ask customer full name.
     *
     * @return $this
     */
    protected function askFullName()
    {
        return $this->askRenderable('conversations.exchange.ask-fullname', function (Answer $answer) {
            $this->setUserStorage(['fullname' => $answer->getText()]);

            return $this->askAccountNumber();
        });
    }

    /**
     * Ask customer account number.
     *
     * @return $this
     */
    protected function askAccountNumber()
    {
        return $this->askRenderable('conversations.exchange.ask-accountnumber', function (Answer $answer) {
            $this->setUserStorage(['customer' => [
                'account_number' => $answer->getText(),
            ]]);

            return tap($this->getUserStorage()->toJson(), function (string $response) {
                $this->destroyUserStorage();

                return $this->say($response);
            });
        });
    }
}
