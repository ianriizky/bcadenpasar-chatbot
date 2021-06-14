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
     * @return \Illuminate\Support\Collection
     */
    protected function getUserStorage(): Collection
    {
        return $this->getBot()->userStorage()->find(
            $this->getUser()->getId()
        );
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
     * @return void
     */
    protected function destroyUserStorage(string $key = null)
    {
        $this->getBot()->userStorage()->delete($key);
    }
}
