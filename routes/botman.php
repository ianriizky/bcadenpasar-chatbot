<?php

use App\Conversations\HomeConservation;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Arr;

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

foreach (HomeConservation::conversations() as $conversation) {
    $commands = Arr::wrap($conversation['command']);

    data_set($commands, 0, '/' . data_get($commands, 0));

    $botman->hears($commands, fn (BotMan $botman) => $botman->startConversation(value($conversation['handler'])));
}

$botman->fallback(function (BotMan $botman) {
    $message = __("Maaf, kami tidak mengenali maksud dari '{$botman->getMessage()->getText()}'.");

    $keyboard = Keyboard::create(Keyboard::TYPE_KEYBOARD)->resizeKeyboard()->oneTimeKeyboard();

    foreach (HomeConservation::conversations(withoutStartCommand: true)->pluck('command') as $command) {
        $keyboard->addRow(KeyboardButton::create(Arr::last($command)));
    }

    $botman->reply($message, additionalParameters: $keyboard->toArray());
});

$botman->listen();
