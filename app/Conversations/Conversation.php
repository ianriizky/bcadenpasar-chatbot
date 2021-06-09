<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation as BaseConversation;
use Illuminate\Contracts\Support\Renderable;

abstract class Conversation extends BaseConversation
{
    /**
     * Run `$this->say()` method with a renderable parameter.
     *
     * @param  \Illuminate\Contracts\Support\Renderable  $view
     * @param  array  $additionalParameters
     * @return $this
     */
    protected function sayRenderable(Renderable $view, array $additionalParameters = [])
    {
        $this->say($view->render(), $additionalParameters);

        return $this;
    }

    /**
     * Run `$this->ask()` method with a renderable parameter.
     *
     * @param  \Illuminate\Contracts\Support\Renderable  $view
     * @param  array|\Closure  $next
     * @param  array  $additionalParameters
     * @return $this
     */
    protected function askRenderable($view, $next, $additionalParameters = [])
    {
        $this->ask($view->render(), $next, $additionalParameters);

        return $this;
    }
}
