<?php

use App\Conversations\ExampleConversation;
use App\Conversations\HelpConversation;
use App\Conversations\StartConservation;
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
$botman->hears('/start', fn (BotMan $botman) => $botman->startConversation(new StartConservation));
$botman->hears('/help', fn (BotMan $botman) => $botman->startConversation(new HelpConversation));

$botman->fallback(function (BotMan $botman) {
    $message = __("Maaf, kami tidak mengenali maksud dari '{$botman->getMessage()->getText()}'.");

    $botman->reply($message);
});
