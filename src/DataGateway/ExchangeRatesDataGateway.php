<?php

namespace App\DataGateway;

use App\Exception\DatabaseNotFoundException;

class ExchangeRatesDataGateway extends DataGateway
{
    /**
     * @throws DatabaseNotFoundException
     */
    public function getExchangeRateByCurrencyCodes(?string $baseCurrencyCode, ?string $targetCurrencyCode): array
    {
        $sql = "SELECT 
                    exrates.id,
                    cur_base.id AS base_currency_id,
                    cur_base.code AS base_currency_code,
                    cur_base.full_name AS base_currency_name,
                    cur_base.sign AS base_currency_sign,
                    cur_target.id AS target_currency_id,
                    cur_target.code AS target_currency_code,
                    cur_target.full_name AS target_currency_name,
                    cur_target.sign AS target_currency_sign,
                    exrates.rate
                FROM exchange_rates AS exrates
                JOIN currencies AS cur_base
                ON cur_base.id=exrates.base_currency_id
                JOIN currencies AS cur_target
                ON cur_target.id=exrates.target_currency_id
                WHERE cur_base.code = :baseCurrencyCode AND cur_target.code = :targetCurrencyCode";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute(['baseCurrencyCode' => $baseCurrencyCode, 'targetCurrencyCode' => $targetCurrencyCode]);
        $result = $statement->fetch();

        if (!$result) {
            throw new DatabaseNotFoundException('Exchange rate for pair not found');
        }

        return $result;
    }

    public function getAllExchangeRates(): array
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

    /**
     * @throws DatabaseNotFoundException
     */
    public function getExchangeRateByIds(int $baseCurrencyId, int $targetCurrencyId): array
    {
        $sql = "SELECT 
                    exrates.id,
                    cur_base.id AS base_currency_id,
                    cur_base.code AS base_currency_code,
                    cur_base.full_name AS base_currency_name,
                    cur_base.sign AS base_currency_sign,
                    cur_target.id AS target_currency_id,
                    cur_target.code AS target_currency_code,
                    cur_target.full_name AS target_currency_name,
                    cur_target.sign AS target_currency_sign,
                    exrates.rate
                FROM exchange_rates AS exrates
                JOIN currencies AS cur_base
                ON cur_base.id=exrates.base_currency_id
                JOIN currencies AS cur_target
                ON cur_target.id=exrates.target_currency_id
                WHERE cur_base.id = :baseCurrencyId AND cur_target.id= :targetCurrencyId";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute(['baseCurrencyId' => $baseCurrencyId, 'targetCurrencyId' => $targetCurrencyId]);
        $result = $statement->fetch();

        if (!$result) {
            throw new DatabaseNotFoundException('Exchange rate for pair not found');
        }

        return $result;
    }

    public function getRateByCurrencyCodes(string $baseCurrencyCode, string $targetCurrencyCode): float
    {
        $sql = "SELECT rate 
                FROM exchange_rates 
                JOIN currencies AS cur_base
                ON exchange_rates.base_currency_id = cur_base.id
                JOIN currencies AS cur_target
                ON  exchange_rates.target_currency_id = cur_target.id
                WHERE cur_base.code = :baseCurrencyCode AND cur_target.code = :targetCurrencyCode";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute(['baseCurrencyCode' => $baseCurrencyCode, 'targetCurrencyCode' => $targetCurrencyCode]);
        $result = $statement->fetch();

        return $result['rate'];
    }
    public function isExchangeRateExists(string $baseCurrencyCode, string $targetCurrencyCode): bool
    {
        $sql = "SELECT exrates.id
                FROM exchange_rates AS exrates
                JOIN currencies AS cur_base
                ON cur_base.code = :baseCurrencyCode
                JOIN currencies AS cur_target
                ON cur_target.code = :targetCurrencyCode
                WHERE exrates.base_currency_id=cur_base.id AND exrates.target_currency_id=cur_target.id";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute(['baseCurrencyCode' => $baseCurrencyCode, 'targetCurrencyCode' => $targetCurrencyCode]);
        $result = $statement->fetch();

        if ($result) {
            return true;
        }

        return false;
    }

    public function insertExchangeRate(array $values): void
    {
        $sql = "INSERT INTO exchange_rates (`base_currency_id`, `target_currency_id`, `rate`)
                VALUES (:baseCurrencyId, :targetCurrencyId, :rate)";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute($values);
    }

    public function updateExchangeRate(array $values): void
    {
        $sql = "UPDATE exchange_rates SET `rate` = :rate WHERE exchange_rates.id = :id";
        $statement = $this->dataBase->prepare($sql);
        $statement->execute($values);
    }
}