<?php

namespace App\DataGateway;

use PDO;

abstract class DataGateway
{
    protected PDO $dataBase;

    /**
     * @param PDO $dbConnection
     */
    public function __construct(PDO $dbConnection)
    {
        $this->dataBase = $dbConnection;
    }

    public function getLastInsertId(): int
    {
        return (int) $this->dataBase->lastInsertId();
    }
}