<?php

namespace App\Conversations;

use App\Enum\Gender;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HomeConservation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return void
     */
    public function run()
    {
        $title = Str::ucfirst(Gender::title($this->getUserStorage('gender')));
        $name = Str::ucfirst($this->getUser()->getFirstName());

        $keyboard = Keyboard::create(Keyboard::TYPE_INLINE)->resizeKeyboard();

        foreach (static::conversations(withoutStartCommand: true) as $conversation) {
            $commands = Arr::wrap($conversation['command']);

            $keyboard->addRow(KeyboardButton::create(Arr::last($commands))
                ->callbackData(Arr::first($commands))
            );
        }

        $response = $this->reply(
            $question = view('conversations.home.confirm-menu', compact('title', 'name'))->render(),
            $additionalParameters = $keyboard->toArray()
        );

        return $this->getBot()->storeConversation($this, next: function (Answer $answer) use ($response) {
            if (!$answer->isInteractiveMessageReply()) {
                return;
            }

            $this->deleteTelegramMessageFromResponse($response);

            if (!$conversation = $this->getConversation($answer->getValue())) {
                return $this->sayFallbackMessage($answer->getText());
            }

            return $this->startConversation($conversation);
        }, question: $question, additionalParameters: $additionalParameters);
    }

    /**
     * Return specific conversation instance based on the given name.
     *
     * @param  string  $name
     * @return \BotMan\BotMan\Messages\Conversations\Conversation|null
     */
    protected function getConversation(string $name): ?Conversation
    {
        if (!$conversation = static::conversations(withoutStartCommand: true)->first(fn ($conversation) =>
            in_array($name, Arr::wrap($conversation['command']))
        )) {
            return null;
        }

        return value($conversation['handler']);
    }

    /**
     * Return list of command and its conversation handler class.
     *
     * @param  bool  $withoutStartCommand
     * @param  bool  $withHidden
     * @return \Illuminate\Support\Collection<array>
     */
    public static function conversations(bool $withoutStartCommand = false, bool $withHidden = false): Collection
    {
        /** @var \Illuminate\Support\Collection<array> $conversations */
        $conversations = collect([
            [
                'command' => 'start',
                'handler' => fn () => new StartConservation,
                'description' => 'Memulai percakapan',
            ],
            [
                'command' => [
                    'exchange',
                    view('conversations.home.reply-menu-exchange')->render(),
                ],
                'handler' => fn () => new ExchangeConversation,
                'description' => 'Melakukan transaksi penukaran uang',
            ],
            [
                'command' => [
                    'help',
                    view('conversations.home.reply-menu-help')->render(),
                ],
                'handler' => fn () => new HelpConversation,
                'description' => 'Panduan cara menggunakan chatbot ini',
            ],
            [
                'command' => [
                    'login',
                    view('conversations.home.reply-menu-login')->render(),
                ],
                'handler' => fn () => new LoginConversation,
                'description' => 'Mendaftarkan Chat ID Telegram pada akun (khusus admin dan staf)',
                'is_hidden' => true,
            ],
            [
                'command' => [
                    'logout',
                    view('conversations.home.reply-menu-logout')->render(),
                ],
                'handler' => fn () => new LogoutConversation,
                'description' => 'Menghapus Chat ID Telegram pada akun (khusus admin dan staf)',
                'is_hidden' => true,
            ],
            [
                'command' => [
                    'ask_telegram_chat_id',
                    view('conversations.home.reply-menu-ask_telegram_chat_id')->render(),
                ],
                'handler' => fn () => new AskTelegramChatIdConversation,
                'description' => 'Mengetahui Chat ID Telegram anda',
                'is_hidden' => true,
            ],
            [
                'command' => [
                    'check_order_status',
                    view('conversations.home.reply-menu-check_order_status')->render(),
                ],
                'handler' => fn () => new CheckOrderStatusConversation,
                'description' => 'Mengetahui status transaksi penukaran uang anda',
                'is_hidden' => true,
            ],
        ]);

        if ($withoutStartCommand) {
            $conversations = $conversations->reject(fn (array $conversation) =>
                in_array('start', Arr::wrap($conversation['command']))
            );
        }

        if (!$withHidden) {
            $conversations = $conversations->reject(fn (array $conversation) =>
                ($conversation['is_hidden'] ?? false)
            );
        }

        return $conversations;
    }
}
