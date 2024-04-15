# currency-exchange

## Project "Currency Exchange API"

REST API for describing currencies and exchange rates. Provide control and management of established currencies and exchange rates, as well as calculate the conversion of arbitrary amounts from one currency to another.

The web interface of the project can be found [HERE](https://github.com/zhukovsd/currency-exchange-frontend)

## Features
- php 8.1
- SQLite
- Slim microframework
- Postman

## Project motivation

- Introduction to MVC
- REST API - correct naming of resources, use of HTTP response codes
- SQL - basic syntax, table creation
- Work with Postman, test endpoints

## Postman API
Public Collection [HERE](https://www.postman.com/supply-astronaut-73332974/workspace/currency-exchange-api/collection/32195670-9c8cc94a-8b0e-494f-850d-66738e8514c8?action=share&creator=32195670)

### Currencies

#### GET `/currencies`
Getting a list of currencies. Sample response:
```json
[
    {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },   
    {
        "id": 0,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    }
]
```

#### GET `/currency/EUR`

Receiving a specific currency. Sample response:
```json
{
    "id": 0,
    "name": "Euro",
    "code": "EUR",
    "sign": "€"
}
```

#### POST `/currencies`

Adding a new currency to the database. The data is sent in the body of the request in the form of form fields (`x-www-form-urlencoded`). The form fields are `name`, `code`, `sign`. An example response is a JSON representation of a record inserted into the database, including its ID:
```json
{
    "id": 0,
    "name": "Euro",
    "code": "EUR",
    "sign": "€"
}
```

### Exchange rates

#### GET `/exchangeRates`

Getting a list of all exchange rates. Sample response:
```json
[
    {
        "id": 0,
        "baseCurrency": {
            "id": 0,
            "name": "United States dollar",
            "code": "USD",
            "sign": "$"
        },
        "targetCurrency": {
            "id": 1,
            "name": "Euro",
            "code": "EUR",
            "sign": "€"
        },
        "rate": 0.99
    }
]
```

#### GET `/exchangeRate/USDRUB`

Receive a specific exchange rate. The currency pair is specified by consecutive currency codes in the request address. Sample response:
```json
{
    "id": 0,
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    },
    "rate": 0.99
}
```

#### POST `/exchangeRates`

Adding a new exchange rate to the database. The data is sent in the body of the request in the form of form fields (`x-www-form-urlencoded`). The form fields are `baseCurrencyCode`, `targetCurrencyCode`, `rate`. Example form fields:
- `baseCurrencyCode` - USD
- `targetCurrencyCode` - EUR
- `rate` - 0.99

An example response is a JSON representation of a record inserted into the database, including its ID:
```json
{
    "id": 0,
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    },
    "rate": 0.99
}
```

#### PATCH `/exchangeRate/USDRUB`

Update the existing exchange rate in the database. The currency pair is specified by consecutive currency codes in the request address. The data is sent in the body of the request in the form of form fields (`x-www-form-urlencoded`). The only form field is `rate`.

An example response is a JSON representation of the updated record in the database, including its ID:
```json
{
    "id": 0,
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    },
    "rate": 0.99
}
```

### Currency exchange

#### GET `/exchange?from=BASE_CURRENCY_CODE&to=TARGET_CURRENCY_CODE&amount=$AMOUNT`

Calculation of the transfer of a certain amount of funds from one currency to another. Example request - GET `/exchange?from=USD&to=AUD&amount=10`.

Sample response:
```json
{
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Australian dollar",
        "code": "AUD",
        "sign": "A€"
    },
    "rate": 1.45,
    "amount": 10.00,
    "convertedAmount": 14.50
}
```

Receiving an exchange rate can follow one of three scenarios. Let's say we make a transfer from currency **A** to currency **B**:
1. In the `ExchangeRates` table there is a currency pair **AB** - take its rate
2. In the `ExchangeRates` table there is a currency pair **BA** - take its rate and calculate the reverse to get **AB**
3. In the `ExchangeRates` table there are currency pairs **USD-A** and **USD-B** - we calculate the **AB** rate from these rates

---

For all requests, in case of an error, the response may look like this:
```json
{
    "message": "Currency not found"
}
```

The value of `message` depends on what kind of error occurred.
