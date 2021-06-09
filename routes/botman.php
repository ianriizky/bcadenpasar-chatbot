<?php

use App\Conversations\ExampleConversation;
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

$botman->hears('Hi', fn (BotMan $botman) => $botman->startConversation(new ExampleConversation));

$botman->fallback(function (BotMan $botman) {
    $message = __("Maaf, kami tidak mengenali maksud dari '{$botman->getMessage()->getText()}'.");

    $botman->reply($message);
});
