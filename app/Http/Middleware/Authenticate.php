<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return response()->json([
            'message' => 'Not authenticated'
            ],401);
        }

        return parent::render($request, $exception);
    }
}
