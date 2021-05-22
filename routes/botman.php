<?php

use App\Http\Controllers\BotmanController;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Facades\BotMan as BotManFacade;

/*
|--------------------------------------------------------------------------
| Botman Routes
|--------------------------------------------------------------------------
|
| This file provide all of the things botman can do.
|
*/

/** @var \BotMan\BotMan\BotMan $botman */
$botman = BotManFacade::getFacadeRoot();

$botman->hears('Hi', function (BotMan $botman) {
    $botman->reply('Hello!');
});

$botman->hears('Start conversation', [BotmanController::class, 'startConversation']);
