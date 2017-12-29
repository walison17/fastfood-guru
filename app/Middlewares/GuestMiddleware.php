<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class GuestMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        return auth()->check() ? redirect('home') : $next($request, $response);
    }
}