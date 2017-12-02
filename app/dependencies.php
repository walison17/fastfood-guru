<?php

$container['view'] = function () {
    $view = new \Projek\Slim\Plates([
        'directory' => config('template.path'),
        'assetPath' => config('webfiles'),
        'fileExtension' => 'phtml',
        'timestampInFilename' => false,
    ]);

    $view->setResponse(app('response'));

    $view->loadExtension(new Projek\Slim\PlatesExtension(
        app('router'),
        app('request')->getUri()
    ));

    return $view;
};

$container['logger'] = function () {
    $logger = new Monolog\Logger(config('logger.name'));
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler(config('logger.path'), config('logger.level')));

    return $logger;
};

$container['auth.provider'] = function() {
    return new \App\Auth\Providers\EloquentProvider;
};

$container['auth.storage'] = function() {
    return new \App\Support\SessionStorage('auth');
};

$container['auth'] = function () use ($container) {
    return new \App\Auth\Authenticator($container['auth.provider'], $container['auth.storage']);
};

$container['db'] = function () {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection(config('db'));
    $capsule->setAsGlobal();

    return $capsule;
};

//controllers
$container['AuthController'] = function () use ($container) {
    return new \App\Controllers\Auth\AuthController($container['auth']);
};