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

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

$container['auth.storage'] = function() {
    return new \App\Support\SessionStorage('auth');
};

$container['auth'] = function () use ($container) {
    return new \App\Core\Auth\Authenticator($container['UsersRepository'], $container['auth.storage']);
};

//repositories
$container['UsersRepository'] = function ($container) {
    return new \App\Db\UsersRepository(\App\Db\Connection\ConnectionFactory::make());
};

$container['UserController'] = function ($container) {
    return new \App\Controllers\UserController($container['UsersRepository']);
};

$container['UserPhotoController'] = function ($container) {
    return new \App\Controllers\UserPhotoController($container['UsersRepository']);
};

//controllers
$container['AuthController'] = function () use ($container) {
    return new \App\Controllers\Auth\AuthController($container['auth.web']);
};

$container['RegistrationController'] = function ($container) {
    return new \App\Controllers\Auth\RegistrationController($container['UsersRepository']);
};

$container['auth.web'] = function ($container) {
    $provider = new \App\Core\Auth\Providers\DatabaseProvider(
        $container['UsersRepository'], 
        $container['auth.storage']
    );

    return new \App\Core\Auth\Authenticator($provider);
}; 