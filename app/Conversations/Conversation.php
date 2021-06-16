<?php

namespace App\Conversations;

use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation as BaseConversation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

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
     * @return void
     */
    protected function destroyUserStorage(string $key = null, array $excepts = [])
    {
        $excepts[] = '_previous_conversation';

        /** @var \Illuminate\Support\Collection $storage */
        $storage = $this->getUserStorage();

        $this->getBot()->userStorage()->delete($key);

        $this->setUserStorage($storage->only($excepts)->toArray());
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
     * @return void
     */
    protected function displayValidationErrorMessage(string $validationErrorMessage = null)
    {
        if ($validationErrorMessage) {
            $this->say('⛔️ ' . $validationErrorMessage);
        }
    }

    /**
     * Return reply response with the fallback message.
     *
     * @param  string  $text
     * @param  string  $view
     * @return $this
     */
    protected function displayFallback(string $text, string $view = 'components.conversations.fallback')
    {
        return $this->say(view($view, compact('text'))->render());
    }

    /**
     * Return previous conversation value.
     *
     * @return \BotMan\BotMan\Messages\Conversations\Conversation
     */
    protected function getPreviousConversation()
    {
        $conversation = $this->getUserStorage('_previous_conversation');

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
}
