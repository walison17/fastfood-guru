<?php 

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Flash\Messages;

class OldInputMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        app('flash')->addMessage('old_input', $request->getParams());

        return $next($request, $response);
    }
}