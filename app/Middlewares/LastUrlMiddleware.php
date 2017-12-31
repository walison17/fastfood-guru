<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class LastUrlMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $request->isGet() && (current_url() != url('auth.showForm') || current_url() != url('auth.login')) 
            ? app('flash')->addMessage('last_url', current_url())
            : app('flash')->addMessage('last_url', get_flash('last_url'));

        return $next($request, $response);
    }
}