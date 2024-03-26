<?php

declare(strict_types=1);

class DatabaseConnection
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO(
            'sqlite:../database.sqlite',
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}