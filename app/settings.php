<?php

return [
    'settings' => [
        'jwt_key' => 'aroundtheworld',
        'debug' => true,

        'whoops.editor' => 'vscode',
        
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        'template' => [
            'path' => __DIR__ . '/../resources/templates/',
        ],

        'webfiles' => __DIR__ . '/../webfiles',

        'logger' => [
            'name' => 'fastfood-guru',
            'path' => __DIR__ . '/../storage/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'temp_dir' => __DIR__ . '/../temp/',

        'storage_path' => __DIR__ . '/../storage/',

        'db' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'test',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8'
        ],
    ],
];
