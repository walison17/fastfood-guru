<?php

namespace App\Db;

abstract class Repository
{
    /** @var \PDO */
    protected $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

        /**
     * builds a string with placeholder "style"
     *
     * @param array $columns
     * @return string
     */
    protected function columnsToPlaceholders(array $columns)
    {
        $placeholders = array_map(function ($column) {
            return ":{$column}";
        }, array_keys($columns));
   
        return implode(', ', $placeholders);
    }
}