<?php

namespace App\DataGateway;

use PDO;

class CurrenciesDataGateway
{
    private PDO $dataBase;

    /**
     * @param PDO $dbConnection
     */
    public function __construct(PDO $dbConnection)
    {
        $this->dataBase = $dbConnection;
    }

    public function getAllCurrencies(): array
    {
        $statement = $this->dataBase->query("SELECT * FROM currencies");
        $result = $statement->fetchAll();

        if (!$result) {
            return [];
        }

        return $result;
    }

}