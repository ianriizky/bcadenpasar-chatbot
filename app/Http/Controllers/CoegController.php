<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Facades\BotMan as BotManFacade;

class CoegController extends Controller
{
    /**
     * The BotMan instance.
     *
     * @var \BotMan\BotMan\BotMan
     */
    protected BotMan $botman;

    /**
     * Create a new instance class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->botman = BotManFacade::getFacadeRoot();
    }

    /**
     * Place for BotMan logic.
     *
     * @return void
     */
    public function handle()
    {
        $this->botman->listen();
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
