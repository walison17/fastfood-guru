<?php

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));

$app->add(new \App\Middlewares\OldInputMiddleware);

$app->add(new \App\Middlewares\LastUrlMiddleware);

// $app->add(new RKA\Middleware\IpAddress());
