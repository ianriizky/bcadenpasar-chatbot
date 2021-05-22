<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use BotMan\BotMan\BotMan;

class BotmanController extends Controller
{
    /**
     * Create a new instance class.
     *
     * @return void
     */
    public function __construct(
        protected BotMan $botman
    ) {
        $this->botman = $botman;
    }

    /**
     * Handle start conversation.
     *
     * @return void
     */
    public function startConversation()
    {
        $this->botman->startConversation(new ExampleConversation);
    }
}
