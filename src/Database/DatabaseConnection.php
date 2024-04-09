<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

class DatabaseConnection
{
    private PDO $connection;

    public function __construct(array $dbSettings)
    {
        $this->connection = new PDO(
            $dbSettings['dsn'],
            $dbSettings['username'],
            $dbSettings['password'],
            $dbSettings['options'],
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}