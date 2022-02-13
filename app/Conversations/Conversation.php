<?php

namespace App\Conversations;

use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation as BaseConversation;
use Closure;
use Illuminate\Contracts\Support\Renderable;
use Symfony\Component\HttpFoundation\Response;

abstract class Conversation extends BaseConversation
{
    /**
     * Run `$this->say()` method with a renderable parameter.
     *
     * @param  \Illuminate\Contracts\Support\Renderable|string  $view
     * @param  array  $viewData
     * @param  array  $viewMergeData
     * @param  array  $additionalParameters
     * @return $this
     */
    protected function sayRenderable($view, array $viewData = [], array $viewMergeData = [], array $additionalParameters = [])
    {
        $view = $view instanceof Renderable ? $view : view($view, $viewData, $viewMergeData);

        $this->say($view->render(), $additionalParameters);

        return $this;
    }

    /**
     * Run `$this->ask()` method with a renderable parameter.
     *
     * @param  \Illuminate\Contracts\Support\Renderable|string  $view
     * @param  array|\Closure  $next
     * @param  array  $viewData
     * @param  array  $viewMergeData
     * @param  array  $additionalParameters
     * @return $this
     */
    protected function askRenderable($view, $next, array $viewData = [], array $viewMergeData = [], array $additionalParameters = [])
    {
        $view = $view instanceof Renderable ? $view : view($view, $viewData, $viewMergeData);

        $this->ask($view->render(), $next, $additionalParameters);

        return $this;
    }

    /**
     * Return BotMan user data.
     *
     * @return \BotMan\BotMan\Interfaces\UserInterface
     */
    protected function getUser(): UserInterface
    {
        return $this->getBot()->getUser();
    }

    /**
     * Return BotMan user storage data.
     *
     * @param  mixed|null  $key
     * @param  mixed|null  $default
     * @return \Illuminate\Support\Collection|mixed
     */
    protected function getUserStorage($key = null, $default = null)
    {
        $storage = $this->getBot()->userStorage()->find(
            $this->getUser()->getId()
        );

        if (is_null($key)) {
            return $storage;
        }

        return $storage->get($key, $default);
    }

    /**
     * Set BotMan user storage data.
     *
     * @param  array  $data
     * @param  string|null  $key
     * @return void
     */
    protected function setUserStorage(array $data, string $key = null)
    {
        $this->getBot()->userStorage()->save($data,$key);
    }

    /**
     * Destroy BotMan user storage data.
     *
     * @param  string|null  $key
     * @param  array  $excepts
     * @param  bool  $forceDestroy
     * @return void
     */
    protected function destroyUserStorage(string $key = null, array $excepts = [], bool $forceDestroy = false)
    {
        if (!$forceDestroy) {
            $excepts[] = '_previous_conversation';

            /** @var \Illuminate\Support\Collection $storage */
            $storage = $this->getUserStorage();
        }

        $this->getBot()->userStorage()->delete($key);

        if (!$forceDestroy) {
            $this->setUserStorage($storage->only($excepts)->toArray());
        }
    }

    /**
     * Return raw message payload from BotMan.
     *
     * @param  string|array|int|null  $key
     * @param  mixed  $default
     * @return array|mixed
     */
    protected function getMessagePayload($key = null, $default = null)
    {
        return data_get(
            json_decode($this->getBot()->getMessage()->getPayload(), true),
            $key, $default
        );
    }

    /**
     * Return reply response with the given validation error message.
     *
     * @param  string|null  $validationErrorMessage
     * @return $this
     */
    protected function displayValidationErrorMessage(string $validationErrorMessage = null)
    {
        if ($validationErrorMessage) {
            $this->say('⛔️ ' . $validationErrorMessage);
        }

        return $this;
    }

    /**
     * Create reply response format with the fallback message.
     *
     * @param  string  $text
     * @param  string  $view
     * @return string
     */
    protected function fallbackMessage(string $text, string $view = 'components.conversations.fallback'): string
    {
        return view($view, compact('text'))->render();
    }

    /**
     * Return reply response with the fallback message.
     *
     * @param  string  $text
     * @param  string  $view
     * @return $this
     */
    protected function sayFallbackMessage(string $text, string $view = 'components.conversations.fallback')
    {
        return $this->say($this->fallbackMessage($text, $view));
    }

    /**
     * Return previous conversation value.
     *
     * @return \BotMan\BotMan\Messages\Conversations\Conversation|null
     */
    protected function getPreviousConversation(): ?BaseConversation
    {
        if (!$conversation = $this->getUserStorage('_previous_conversation')) {
            return null;
        }

        return new $conversation;
    }

    /**
     * Set previous conversation value.
     *
     * @param  string|\BotMan\BotMan\Messages\Conversations\Conversation  $conversation
     * @return $this
     */
    protected function setPreviousConversation($conversation)
    {
        if (is_a($conversation, BaseConversation::class)) {
            $conversation = is_object($conversation) ? get_class($conversation) : $conversation;

            $this->setUserStorage(['_previous_conversation' => $conversation]);
        }

        return $this;
    }

    /**
     * Start previous conversation.
     *
     * @return void
     */
    protected function startPreviousConversation()
    {
        $this->getBot()->startConversation($this->getPreviousConversation());
    }

    /**
     * Start conversation.
     *
     * @param  string|\BotMan\BotMan\Messages\Conversations\Conversation  $conversation
     * @return void
     */
    protected function startConversation($conversation)
    {
        if (is_a($conversation, BaseConversation::class)) {
            $conversation = is_object($conversation) ? $conversation : new $conversation;

            $this->getBot()->startConversation($conversation);
        }
    }

    /**
     * Send reply message request and return the given response.
     *
     * @param  string|\BotMan\BotMan\Messages\Outgoing\OutgoingMessage|\BotMan\BotMan\Messages\Outgoing\Question  $message
     * @param  array  $additionalParameters
     * @param  \Closure|null  $callback
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function reply($message, array $additionalParameters = [], Closure $callback = null)
    {
        $response = $this->getBot()->reply($message, $additionalParameters);

        if (is_callable($callback)) {
            $callback($response);
        }

        return $response;
    }

    /**
     * Send request to Telegram API for deleting specified message.
     *
     * @param  int|string  $chat_id
     * @param  int  $message
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \BadMethodCallException
     */
    protected function deleteTelegramMessage($chat_id, $message_id)
    {
        return $this->getBot()->sendRequest('deleteMessage', compact('chat_id', 'message_id'));
    }

    /**
     * Send request to Telegram API for deleting specified message based on the given response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function deleteTelegramMessageFromResponse(Response $response)
    {
        $responseBody = json_decode($response->getContent(), true);

        return $this->deleteTelegramMessage(
            data_get($responseBody, 'result.chat.id'),
            data_get($responseBody, 'result.message_id')
        );
    }
}
