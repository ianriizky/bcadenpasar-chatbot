<?php

use App\Http\Controllers\BotmanController;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Botman Routes
|--------------------------------------------------------------------------
|
| This file provide all of the things botman can do.
|
*/

Route::match(['get', 'post'], '/botman', function (BotMan $botman) {
    $botman->hears('Hi', function (BotMan $botman) {
        $botman->reply('Hello!');
    });

    $botman->hears('Start conversation', BotmanController::class . '@startConversation');

    $botman->listen();
});
