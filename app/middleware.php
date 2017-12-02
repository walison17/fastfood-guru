<?php


$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));


// $app->add(new RKA\Middleware\IpAddress());

// $app->add(new App\Middlewares\AjaxResponseMiddleware());