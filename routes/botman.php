<?php

use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;

/*
|--------------------------------------------------------------------------
| Botman Routes
|--------------------------------------------------------------------------
|
| This file provide all of the things botman can do.
|
*/

/** @var \BotMan\BotMan\BotMan $botman */
$botman = resolve('botman');

$botman->hears('Hi', function (BotMan $botman) {
    $botman->reply('Hello!');
});

$botman->hears('/start', BotManController::class . '@startConversation');
