<?php

use App\Conversations\ExampleConversation;
use App\Conversations\ExchangeConversation;
use App\Conversations\HelpConversation;
use App\Conversations\StartConservation;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

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

$exchange = view('conversations.home.reply-menu-exchange')->render();

$botman->hears('Hi', fn (BotMan $botman) => $botman->startConversation(new ExampleConversation));
$botman->hears('/start', fn (BotMan $botman) => $botman->startConversation(new StartConservation));
$botman->hears(['/exchange', $exchange], fn (BotMan $botman) => $botman->startConversation(new ExchangeConversation));
$botman->hears('/help', fn (BotMan $botman) => $botman->startConversation(new HelpConversation));

$botman->fallback(function (BotMan $botman) use ($exchange) {
    $message = __("Maaf, kami tidak mengenali maksud dari '{$botman->getMessage()->getText()}'.");

    $botman->reply($message, additionalParameters: Keyboard::create(Keyboard::TYPE_KEYBOARD)->resizeKeyboard()->oneTimeKeyboard()->addRow(
        KeyboardButton::create($exchange)
    )->toArray());
});

$botman->listen();
