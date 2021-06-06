<?php

use App\Conversations\ExampleConversation;
use App\Conversations\HomeConservation;
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

$botman->hears('/start', fn (BotMan $botman) => $botman->startConversation(new HomeConservation));
