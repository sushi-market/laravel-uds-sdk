<?php

namespace SushiMarket\UdsSdk\Services;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use SushiMarket\UdsSdk\Interfaces\HttpClientInterface;

class HttpClientFake extends HttpClientProduction implements HttpClientInterface
{
    public const COMPANY_ID = 111111111111;

    public const API_KEY = 'test-valid-key';

    public const SERVER_ERROR_API_KEY = 'server-error-key';

    public const CODE = '123456';

    public const CERTIFICATE_CODE = '654321';

    public const PHONE = '+79999999999';

    public const UID = 'xxxxxxxx-test-user-uuid-xxxxxxxx';

    public const TRANSACTION_ID = 1234567890;

    public const TRANSACTION_ID_WITH_CERTIFICATE = 987654321;

    public const VALID_TOTAL = 1000;

    protected string $authorization;

    protected string $authorizationServerError;

    protected PendingRequest $client;

    public function __construct(string $baseUrl, string $companyId, string $token)
    {
        $this->authorization = 'Basic ' . base64_encode(self::COMPANY_ID . ':' . self::API_KEY);
        $this->authorizationServerError = 'Basic ' . base64_encode(self::COMPANY_ID . ':' . self::SERVER_ERROR_API_KEY);

        Http::fake(function (Request $request) use ($baseUrl, $token) {
            $requestAuthorization = $request->header('Authorization')[0] ?? '';
            $requestBody = $request->body();
            $requestJson = json_decode($requestBody);
            $responseBody = self::fakeNotFoundResponse();
            $responseCode = 404;

            if ($requestAuthorization === $this->authorizationServerError) {
                return Http::response('Server Error', 500);
            }

            if ($requestAuthorization !== $this->authorization) {
                $responseBody = self::fakeUnauthorizedResponse();
                $responseCode = 401;
            } else {
                if ($request->method() === 'GET' && $request->url() === "$baseUrl/settings") {
                    $responseBody = self::fakeSettingsResponse();
                    $responseCode = 200;
                }

                if ($request->method() === 'POST' && $request->url() === "$baseUrl/operations/calc") {
                    $code = $requestJson->code ?? null;
                    $phone = $requestJson?->participant?->phone ?? null;
                    $uid = $requestJson?->participant?->uid ?? 123;

                    if ($code === self::CODE || $phone === self::PHONE || $uid === self::UID) {
                        $responseBody = self::fakeCalculateTransactionResponse();
                        $responseCode = 200;
                    }

                    if ($code === self::CERTIFICATE_CODE) {
                        $responseBody = self::fakeCalculateTransactionByCertificateResponse();
                        $responseCode = 200;
                    }

                    if (!isset($requestJson->receipt->total)) {
                        $responseBody = self::fakeBadRequestResponse();
                        $responseCode = 400;
                    }
                }

                if ($request->method() === 'POST' && $request->url() === "$baseUrl/operations") {
                    $code = $requestJson->code ?? null;
                    $phone = $requestJson?->participant?->phone ?? null;
                    $uid = $requestJson?->participant?->uid ?? 123;

                    if ($code === self::CODE || $phone === self::PHONE || $uid === self::UID) {
                        $responseBody = self::fakeCreateTransactionResponse();
                        $responseCode = 200;
                    }

                    if ($code === self::CERTIFICATE_CODE) {
                        $responseBody = self::fakeCreateTransactionByCertificateResponse();
                        $responseCode = 200;
                    }

                    if (!isset($requestJson->receipt->total)) {
                        $responseBody = self::fakeBadRequestResponse();
                        $responseCode = 400;
                    }

                    if (isset($requestJson->receipt->total) && $requestJson->receipt->total !== self::VALID_TOTAL) {
                        $responseBody = self::fakeInvalidCheckSumResponse();
                        $responseCode = 400;
                    }
                }

                if ($request->method() === 'POST' && $request->url() === "$baseUrl/operations/" . self::TRANSACTION_ID . '/refund') {
                    $responseBody = self::fakeRefundTransactionResponse();
                    $responseCode = 200;
                }

                if ($request->method() === 'POST' && $request->url() === "$baseUrl/operations/" . self::TRANSACTION_ID_WITH_CERTIFICATE . '/refund') {
                    $responseBody = self::fakeRefundTransactionByCertificateResponse();
                    $responseCode = 200;
                }
            }

            return Http::response($responseBody, $responseCode);
        });

        parent::__construct(...func_get_args());
    }

    static function fakeUnauthorizedResponse(): string
    {
        return '{
            "errorCode": "unauthorized",
            "message": "The request requires user authentication."
        }';
    }

    static function fakeNotFoundResponse(): string
    {
        return '{
            "errorCode":"notFound",
            "message":"The page you requested is not found."
        }';
    }

    static function fakeBadRequestResponse(): string
    {
        return '{
            "errorCode":"badRequest",
            "message":"Bad Request"
        }';
    }

    static function fakeSettingsResponse(): string
    {
        return '{
            "baseDiscountPolicy": "CHARGE_SCORES",
            "promoCode": "test123",
            "loyaltyProgramSettings": {
                "membershipTiers": [
                    {
                        "uid": "86f366bd-6673-4231-a4a7-f04d46cf8fd9",
                        "name": "Тест - 1",
                        "conditions": {
                            "effectiveInvitedCount": null,
                            "totalCashSpent": {
                                "target": 20000
                            }
                        },
                        "maxScoresDiscount": 50,
                        "rate": 4
                    },
                    {
                        "uid": "8f219a81-3093-44ca-8f83-fb675771dded",
                        "name": "Тест - 2",
                        "conditions": {
                            "effectiveInvitedCount": null,
                            "totalCashSpent": {
                                "target": 50000
                            }
                        },
                        "maxScoresDiscount": 50,
                        "rate": 5
                    },
                    {
                        "uid": "3129cb93-5d9b-4acc-8f40-e0800f290568",
                        "name": "Тест - 3",
                        "conditions": {
                            "effectiveInvitedCount": {
                                "target": 100
                            },
                            "totalCashSpent": null
                        },
                        "maxScoresDiscount": 50,
                        "rate": 7
                    },
                    {
                        "uid": "6061a1a7-3be5-410f-b972-c82327b53850",
                        "name": "Тест - 4",
                        "conditions": {
                            "effectiveInvitedCount": null,
                            "totalCashSpent": {
                                "target": 200000
                            }
                        },
                        "maxScoresDiscount": 100,
                        "rate": 10
                    }
                ],
                "deferPointsForDays": null,
                "referralCashbackRates": [
                    1,
                    0,
                    0
                ],
                "baseMembershipTier": {
                    "uid": "base",
                    "name": "Тест - 1",
                    "conditions": {
                        "effectiveInvitedCount": null,
                        "totalCashSpent": null
                    },
                    "maxScoresDiscount": 50,
                    "rate": 3
                },
                "receiptLimit": null,
                "firstPurchasePoints": null,
                "cashierAward": null,
                "referralReward": 30,
                "maxScoresDiscount": 50
            },
            "currency": "RUB",
            "id": ' . self::COMPANY_ID . ',
            "slug": "test-slug",
            "writeInvoice": false,
            "purchaseByPhone": true,
            "name": "Тестовая интеграция"
        }';
    }

    static function fakeCalculateTransactionResponse(): string
    {
        return '{
            "purchase": {
                "cash": 700,
                "discountAmount": 0.00,
                "unredeemableTotal": 0.00,
                "certificatePoints": 0.00,
                "maxPoints": 300,
                "extras": {},
                "netDiscount": 300,
                "points": 300,
                "netDiscountPercent": 0.3,
                "cashBack": 210,
                "total": 1000,
                "cashTotal": 700,
                "skipLoyaltyTotal": 0.00,
                "pointsPercent": 0.3,
                "maxScoresDiscount": 50,
                "discountPercent": 0
            },
            "user": {
                "phone": "+79999999999",
                "gender": "NOT_SPECIFIED",
                "uid": "' . self::UID . '",
                "birthDate": "2000-01-00",
                "channelName": "UDS App",
                "tags": [
                    {"id":1997,"name":"Постоянный гость"},
                    {"id":1998,"name":"Тег 2"},
                    {"id":1999,"name":"Тег 3"}
                ],
                "email": "email@example.com",
                "avatar": null,
                "participant": {
                    "inviterId": null,
                    "discountRate": 0.00,
                    "cashbackRate": 3,
                    "dateCreated": "2022-01-01T00:00:00.000Z",
                    "points": 300,
                    "id": 1099576113739,
                    "lastTransactionTime": "2023-01-01T00:00:00.000Z",
                    "membershipTier": {
                        "uid": "base",
                        "name": "Тест - 1",
                        "conditions": {
                            "effectiveInvitedCount": null,
                            "totalCashSpent": null
                        },
                        "maxScoresDiscount": 50,
                        "rate": 3
                    }
                },
                "displayName": "Тестовый Профиль"
            }
        }';
    }

    static function fakeCalculateTransactionByCertificateResponse(): string
    {
        return '{
            "purchase": {
                "cash": 700,
                "discountAmount": 0.00,
                "unredeemableTotal": 0.00,
                "certificatePoints": 300.00,
                "maxPoints": 300,
                "extras": {},
                "netDiscount": 300,
                "points": 0.00,
                "netDiscountPercent": 0.3,
                "cashBack": 210,
                "total": 1000,
                "cashTotal": 700,
                "skipLoyaltyTotal": 0.00,
                "pointsPercent": 0.3,
                "maxScoresDiscount": 50,
                "discountPercent": 0
            },
            "user": {
                "phone": "+79999999999",
                "gender": "NOT_SPECIFIED",
                "uid": "' . self::UID . '",
                "birthDate": "2000-01-00",
                "channelName": "UDS App",
                "tags": [
                    {"id":1997,"name":"Постоянный гость"},
                    {"id":1998,"name":"Тег 2"},
                    {"id":1999,"name":"Тег 3"}
                ],
                "email": "email@example.com",
                "avatar": null,
                "participant": {
                    "inviterId": null,
                    "discountRate": 0.00,
                    "cashbackRate": 3,
                    "dateCreated": "2022-01-01T00:00:00.000Z",
                    "points": 300,
                    "id": 1099576113739,
                    "lastTransactionTime": "2023-01-01T00:00:00.000Z",
                    "membershipTier": {
                        "uid": "base",
                        "name": "Тест - 1",
                        "conditions": {
                            "effectiveInvitedCount": null,
                            "totalCashSpent": null
                        },
                        "maxScoresDiscount": 50,
                        "rate": 3
                    }
                },
                "displayName": "Тестовый Профиль"
            }
        }';
    }

    static function fakeCreateTransactionResponse(): string
    {
        return '{
            "cash": 700,
            "action": "PURCHASE",
            "cashier": {
                "id": 1234567890,
                "displayName": "Тестовый кассир"
            },
            "customer": {
                "uid": "' . self::UID . '",
                "id": 1234567890,
                "membershipTier": {
                    "conditions": {
                        "effectiveInvitedCount": null,
                        "totalCashSpent": null
                    },
                    "maxScoresDiscount": 50,
                    "name": "Тест - 1",
                    "rate": 3,
                    "uid": "base"
                },
                "displayName": "Тестовый Профиль"
            },
            "origin": null,
            "dateCreated": "2022-01-01T00:00:00.000Z",
            "points": -300,
            "id": ' . self::TRANSACTION_ID . ',
            "total": 1000,
            "receiptNumber": "123456",
            "branch": {
                "id": 1234567890,
                "displayName": "Тестовый филиал"
            },
            "state": "NORMAL"
        }';
    }

    static function fakeCreateTransactionByCertificateResponse(): string
    {
        return '{
            "cash": 700,
            "action": "PURCHASE",
            "cashier": {
                "id": 1234567890,
                "displayName": "Тестовый кассир"
            },
            "customer": {
                "uid": "' . self::UID . '",
                "id": 1234567890,
                "membershipTier": {
                    "conditions": {
                        "effectiveInvitedCount": null,
                        "totalCashSpent": null
                    },
                    "maxScoresDiscount": 50,
                    "name": "Тест - 1",
                    "rate": 3,
                    "uid": "base"
                },
                "displayName": "Тестовый Профиль"
            },
            "origin": null,
            "dateCreated": "2022-01-01T00:00:00.000Z",
            "points": 0,
            "id": ' . self::TRANSACTION_ID . ',
            "total": 1000,
            "receiptNumber": "123456",
            "branch": {
                "id": 1234567890,
                "displayName": "Тестовый филиал"
            },
            "state": "NORMAL"
        }';
    }

    static function fakeRefundTransactionResponse(): string
    {
        return '{
            "cash": -90.00,
            "action": "PURCHASE",
            "cashier": {
                "id": 1234567890,
                "displayName": "Тестовый кассир"
            },
            "customer": {
                "uid": "' . self::UID . '",
                "id": 1234567890,
                "membershipTier": null,
                "displayName": "Тестовый Профиль"
            },
            "origin": {
                "id": ' . self::TRANSACTION_ID . '
            },
            "dateCreated": "2022-01-01T00:00:00.000Z",
            "points": 10.00,
            "id": ' . self::TRANSACTION_ID + 1 . ',
            "total": -100,
            "receiptNumber": null,
            "branch": {
                "id": 1234567890,
                "displayName": "Тестовый филиал"
            },
            "state": "REVERSAL"
        }';
    }

    static function fakeRefundTransactionByCertificateResponse(): string
    {
        return '{
            "cash": -90.00,
            "action": "PURCHASE",
            "cashier": {
                "id": 1234567890,
                "displayName": "Тестовый кассир"
            },
            "customer": {
                "uid": "' . self::UID . '",
                "id": 1234567890,
                "membershipTier": null,
                "displayName": "Тестовый Профиль"
            },
            "origin": {
                "id": ' . self::TRANSACTION_ID . '
            },
            "dateCreated": "2022-01-01T00:00:00.000Z",
            "points": 0.00,
            "id": ' . self::TRANSACTION_ID + 1 . ',
            "total": -100,
            "receiptNumber": null,
            "branch": {
                "id": 1234567890,
                "displayName": "Тестовый филиал"
            },
            "state": "REVERSAL"
        }';
    }

    static function fakeInvalidCheckSumResponse(): string
    {
        return '{
            "errorCode":"invalidChecksum",
            "message":"An error has occurred"
        }';
    }
}