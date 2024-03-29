<?php

namespace App\DataGateway;

use App\Exception\CurrencyNotFoundException;
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

    /**
     * @throws CurrencyNotFoundException
     */
    public function getCurrency(string $currencyCode): array
    {
        $sql = "SELECT * FROM currencies WHERE `code` = :code";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute(['code' => $currencyCode]);
        $result = $statement->fetch();

        if (!$result) {
            throw new CurrencyNotFoundException();
        }

        return $result;
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