<?php


$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));

$app->add(new \App\Middlewares\OldInputMiddleware);

// $app->add(new RKA\Middleware\IpAddress());
