<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Session;

class AuthenticateOnVerifying extends Authenticate
{
    /**
     * {@inheritDoc}
     */
    protected function unauthenticated($request, array $guards)
    {
        Session::flash('verifying', true);

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
}
