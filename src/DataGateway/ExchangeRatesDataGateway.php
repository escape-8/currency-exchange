<?php

namespace App\DataGateway;

class ExchangeRatesDataGateway extends DataGateway
{
    private PDO $dataBase;

    /**
     * @param PDO $dbConnection
     */
    public function __construct(PDO $dbConnection)
    {
        $this->dataBase = $dbConnection;
    }

}