<?php

namespace App\Db\Connection;

use \PDO;

class ConnectionFactory
{
    private static $connection;

    public static function make()
    {
        if (! isset(self::$connection)) {
            $dsn = sprintf(
                '%s:host=%s;dbname=%s;charset=%s',
                config('db.driver'),
                config('db.host'),
                config('db.database'),
                config('db.charset')
            );

            self::$connection = new PDO($dsn, config('db.username'), config('db.password'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }

        return self::$connection;
    }
}