*UDS Laravel Package* - PHP SDK [Laravel](https://github.com/laravel/laravel) пакет для взаимодействия с API [uds.app](https://uds.app) от [sushi-market](https://sushi-market.com/)

<p align="center">
    <a href="https://github.com/sushi-market/laravel-uds-sdk/actions"><img src="https://github.com/sushi-market/laravel-uds-sdk/actions/workflows/test.yml/badge.svg" alt="Build Status"></a>
    <a href="https://github.com/sushi-market/laravel-uds-sdk/releases"><img src="https://img.shields.io/github/release/sushi-market/laravel-uds-sdk.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/sushi-market/laravel-uds-sdk"><img src="https://img.shields.io/packagist/dt/sushi-market/laravel-uds-sdk.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://github.com/sushi-market/laravel-uds-sdk/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="Software License"></a>
</p>

## Requirements
- PHP 8.1, 8.2
- Laravel 10.0
- guzzlehttp/guzzle 7.8

## Установка
Вы можете установить пакет через composer:

```shell script
composer require sushi-market/laravel-uds-sdk
```

## Немного о UDS
SDK использует api.uds.app/partner/v2
Всю информацию по ответам и параметрам можно найти в [документации UDS](https://docs.uds.app)

## Методы
- **Настройки**
    - [Получение настроек компании](https://github.com/sushi-market/laravel-uds-sdk#%D0%BF%D0%BE%D0%BB%D1%83%D1%87%D0%B5%D0%BD%D0%B8%D0%B5-%D0%BD%D0%B0%D1%81%D1%82%D1%80%D0%BE%D0%B5%D0%BA-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D0%B8)
- **Операции**
    - [Рассчитать информацию по операции](https://github.com/sushi-market/laravel-uds-sdk#%D1%80%D0%B0%D1%81%D1%81%D1%87%D0%B8%D1%82%D0%B0%D1%82%D1%8C-%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D1%8E-%D0%BF%D0%BE-%D0%BE%D0%BF%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D0%B8-uds-%D0%B4%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%86%D0%B8%D1%8F)
    - [Проведение операции](https://github.com/sushi-market/laravel-uds-sdk#%D0%BF%D1%80%D0%BE%D0%B2%D0%B5%D0%B4%D0%B5%D0%BD%D0%B8%D0%B5-%D0%BE%D0%BF%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D0%B8-uds-%D0%B4%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%86%D0%B8%D1%8F)
    - [Операция возврата](https://github.com/sushi-market/laravel-uds-sdk#%D0%BE%D0%BF%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D1%8F-%D0%B2%D0%BE%D0%B7%D0%B2%D1%80%D0%B0%D1%82%D0%B0-uds-%D0%B4%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%86%D0%B8%D1%8F)

## Настройки
### Получение настроек компании
`getSettings` - Получение настроек компании, способа предоставления скидки и промокода для вступления в компанию. Способ предоставления скидки настраивается в UDS.

## Операции
### Рассчитать информацию по операции [[UDS документация](https://docs.uds.app/#tag/Operations/paths/~1operations~1calc/post)]
`calculateTransaction` - Информация о доступных бонусных баллах для списания или размере скидки (в зависимости от настроек бонусной программы), сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов, которые будут начислены после выполнения операции.
Параметры функции:
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS
* string $code - Платёжный код
* array $participant - Информация о клиенте. Должен содержать идентификатор клиента в UDS или номер телефона

`calculateTransactionByCode` - Информация о доступных бонусных баллах по **платёжному коду** для списания или размере скидки (в зависимости от настроек бонусной программы), сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов, которые будут начислены после выполнения операции.
Параметры функции:
* string $code - Платёжный код
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS

`calculateTransactionByPhone` - Информация о доступных бонусных баллах по **номеру телефона** для списания или размере скидки (в зависимости от настроек бонусной программы), сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов, которые будут начислены после выполнения операции.
Параметры функции:
* string $phone - Номер телефона клиента
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS

`calculateTransactionByUid` - Информация о доступных бонусных баллах по **идентификатору клиента** для списания или размере скидки (в зависимости от настроек бонусной программы), сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов, которые будут начислены после выполнения операции.
Параметры функции:
* string $uid - Идентификатор клиента
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS

### Проведение операции [[UDS документация](https://docs.uds.app/#tag/Operations/paths/~1operations/post)]
`createTransaction` - Проведение операции в UDS. После успешного завершения операция отобразится в списке операций в UDS, а администратор и клиент получат push-уведомление о покупке.
Параметры функции:
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS
* ExternalCashier $cashier - Информация о сотруднике. (externalId, name)
* array $tags - Массив объектов Tag, назначаемых клиенту при проведении операции
* Nonce $nonce - Уникальный идентификатор операции. Генерируется автоматически при создании экземпляра класса
* string $code - Платёжный код
* array $participant - Информация о клиенте. Должен содержать идентификатор клиента в UDS или номер телефона

`createTransactionByCode` - Проведение операции в UDS по **платёжному коду**
Параметры функции:
* string $code - Платёжный код
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS
* ExternalCashier $cashier - Информация о сотруднике. (externalId, name)
* array $tags - Массив объектов Tag, назначаемых клиенту при проведении операции
* Nonce $nonce - Уникальный идентификатор операции. Генерируется автоматически при создании экземпляра класса

`createTransactionByPhone` - Проведение операции в UDS по **номеру телефона**
Параметры функции:
* string $phone - Номер телефона клиента
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS
* ExternalCashier $cashier - Информация о сотруднике. (externalId, name)
* array $tags - Массив объектов Tag, назначаемых клиенту при проведении операции
* Nonce $nonce - Уникальный идентификатор операции. Генерируется автоматически при создании экземпляра класса

`createTransactionByUid` - Проведение операции в UDS по **идентификатору клиента**
Параметры функции:
* string $uid - Идентификатор клиента
* array $receipt - Информация о чеке. Ключи соответствуют документации UDS
* ExternalCashier $cashier - Информация о сотруднике. (externalId, name)
* array $tags - Массив объектов Tag, назначаемых клиенту при проведении операции
* Nonce $nonce - Уникальный идентификатор операции. Генерируется автоматически при создании экземпляра класса

### Операция возврата [[UDS документация](https://docs.uds.app/#tag/Operations/paths/~1operations~1{id}~1refund/post)]
`refundTransaction` - Возврат операции по ее идентификатору. Если указан параметр partialAmount, то возврат будет частичным, иначе - полным
Параметры функции:
* int $id - Идентификатор возвращаемой операции
* float $partialAmount - сумма возврата

## Хелпер-функции
`testCredentials` - Проверяет $companyId и $apiKey на доступность, возвращает true в случае успеха, false в случае ошибки.

## Примеры
### Создание транзакции
```php
app(Uds::class, [
    'companyId' => 123456789,
    'apiKey' => 'xxxxxxxxxxxxxxx',
])->createTransactionByCode('123456', [
    'total' => 1000,
    'cash' => 1000,
    'points' => 0,
    'number' => '123456',
    'skipLoyaltyTotal' => null,
    'unredeemableTotal' => null,
])
```

### Полный возврат транзакции
```php
app(Uds::class, [
    'companyId' => 123456789,
    'apiKey' => 'xxxxxxxxxxxxxxx',
])->refundTransaction(123456789)
```

### Частичный возврат транзакции
```php
app(Uds::class, [
    'companyId' => 123456789,
    'apiKey' => 'xxxxxxxxxxxxxxx',
])->refundTransaction(123456789, 100)
```

### Больше примеров можно найти в тестах