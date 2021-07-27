<?php

namespace App\Listeners;

use App\Contracts\Event\CanSendTelegramNotification;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SendTelegramNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Contracts\Event\CanSendTelegramNotification  $event
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function handle(CanSendTelegramNotification $event)
    {
        /** @var \BotMan\BotMan\BotMan $botman */
        $botman = resolve('botman');

        $response = $botman->say(
            $event->getTelegramMessage(),
            $event->getTelegramChatId(),
            TelegramDriver::class
        );

        if (!$response->isOk()) {
            throw new HttpException($response->getStatusCode(), $response->getContent());
        }
    }
}
