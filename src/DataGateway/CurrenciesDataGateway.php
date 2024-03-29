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
        $sql = "SELECT * FROM currencies";
        $statement = $this->dataBase->query($sql);
        $result = $statement->fetchAll();

        if (!$result) {
            return [];
        }

        return $result;
    }

}