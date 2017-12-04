<?php

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';

// session_save_path(__DIR__ . '/temp/session/');
session_start();

/** @var array */
$settings = require __DIR__ . '/app/settings.php';

/** @var Container */
$container = App\Core\Container::instance($settings);

/** @var Slim */
$app = new \Slim\App($container);

date_default_timezone_set('America/Recife');

require __DIR__ . '/app/dependencies.php';

require __DIR__ . '/app/Core/helpers.php';

require __DIR__ . '/app/middleware.php';

require __DIR__ . '/app/routes.php';

app('db')->bootEloquent();

$app->run();
