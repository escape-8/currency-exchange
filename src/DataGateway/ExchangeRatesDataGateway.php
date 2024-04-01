<?php

namespace App\DataGateway;

class ExchangeRatesDataGateway extends DataGateway
{
    public function getAllCurrencies(): array
    {
        $sql = "SELECT 
                    exrates.id,
                    base_target.id AS base_currency_id,
                    base_target.code AS base_currency_code,
                    base_target.full_name AS base_currency_name,
                    base_target.sign AS base_currency_sign,
                    cur_target.id AS target_currency_id,
                    cur_target.code AS target_currency_code,
                    cur_target.full_name AS target_currency_name,
                    cur_target.sign AS target_currency_sign,
                    exrates.rate
                FROM exchange_rates AS exrates
                JOIN currencies AS cur_target
                ON target_currency_id=cur_target.id
                JOIN currencies AS base_target
                ON base_currency_id=base_target.id;";

        $statement = $this->dataBase->query($sql);
        $result = $statement->fetchAll();

        if (!$result) {
            return [];
        }

        return $result;
    }
}