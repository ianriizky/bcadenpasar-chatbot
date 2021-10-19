<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritDoc}
     */
    protected $dontReport = [
        //
    ];

    /**
     * {@inheritDoc}
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->renderable(function (Throwable $exception, Request $request) {
            //
        });
    }
}
