<?php

use Respect\Validation\Validator as v;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

// session_save_path(__DIR__ . '/temp/session/');
session_start();

/** @var array */
$settings = require __DIR__ . '/settings.php';

/** @var Container */
$container = App\Core\Container::instance($settings);

/** @var Slim */
$app = new \Slim\App($container);

date_default_timezone_set('America/Recife');

require __DIR__ . '/dependencies.php';

require __DIR__ . '/helpers.php';

require __DIR__ . '/middleware.php';

require __DIR__ . '/routes.php';

v::with('App\\Core\\Validation\\Rules\\');