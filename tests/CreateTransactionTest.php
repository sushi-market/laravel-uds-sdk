<?php

namespace SushiMarket\UdsSdk\Test;

use SushiMarket\UdsSdk\Exceptions\BadRequestException;
use SushiMarket\UdsSdk\Exceptions\InternalServerErrorException;
use SushiMarket\UdsSdk\Exceptions\InvalidCheckSumException;
use SushiMarket\UdsSdk\Exceptions\NotFoundException;
use SushiMarket\UdsSdk\Resources\ExternalCashier;
use SushiMarket\UdsSdk\Resources\Nonce;
use SushiMarket\UdsSdk\Resources\Responses\CreateTransactionResponse;
use SushiMarket\UdsSdk\Services\HttpClientFake;

class CreateTransactionTest extends TestCase
{
    protected array $transactionData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionData = [
            [
                'total' => 1000,
                'cash' => 1000,
                'points' => 0,
                'number' => '123456',
                'skipLoyaltyTotal' => null,
                'unredeemableTotal' => null,
            ],
            new ExternalCashier([
                'externalId' => 123,
                'name' => 'Тестовый кассир',
            ]),
            [
                1997
            ],
            new Nonce(),
        ];
    }

    public function test_by_code_is_instance_of_create_transaction_response()
    {
        $this->assertInstanceOf(CreateTransactionResponse::class, $this->uds->createTransactionByCode(HttpClientFake::CODE, ...$this->transactionData));
    }

    public function test_by_code_response_body_discount_is_300()
    {
        $response = $this->uds->createTransactionByCode(HttpClientFake::CODE, ...$this->transactionData);
        $this->assertEquals(300, $response->discount);
    }

    public function test_by_code_response_body_is_not_certificate() {
        $response = $this->uds->createTransactionByCode(HttpClientFake::CODE, ...$this->transactionData);
        $this->assertFalse($response->isCertificate);
    }

    public function test_by_certificate_code_response_body_is_certificate()
    {
        $response = $this->uds->createTransactionByCode(HttpClientFake::CERTIFICATE_CODE, ...$this->transactionData);
        $this->assertTrue($response->isCertificate);
    }

    public function test_by_certificate_code_response_body_discount_is_300() {
        $response = $this->uds->createTransactionByCode(HttpClientFake::CERTIFICATE_CODE, ...$this->transactionData);
        $this->assertEquals(300.0, $response->discount);
    }

    public function test_by_invalid_code_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->createTransactionByCode('999999', ...$this->transactionData);
    }

    public function test_by_phone_is_instance_of_create_transaction_response()
    {
        $this->assertInstanceOf(CreateTransactionResponse::class, $this->uds->createTransactionByPhone(HttpClientFake::PHONE, ...$this->transactionData));
    }

    public function test_by_invalid_phone_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->createTransactionByPhone('+79999998888', ...$this->transactionData);
    }

    public function test_by_uid_is_instance_of_create_transaction_response()
    {
        $this->assertInstanceOf(CreateTransactionResponse::class, $this->uds->createTransactionByUid(HttpClientFake::UID, ...$this->transactionData));
    }

    public function test_by_invalid_uid_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->createTransactionByUid('xxxxxxxx-invalid-user-uuid-xxxxxxxx', ...$this->transactionData);
    }

    public function test_server_error_exception()
    {
        $this->expectException(InternalServerErrorException::class);
        $this->udsServerError->createTransactionByUid(HttpClientFake::UID, ...$this->transactionData);
    }

    public function test_invalid_check_sum_exception()
    {
        $this->expectException(InvalidCheckSumException::class);
        $transactionData = $this->transactionData;
        $transactionData[0]['total'] = 10001;
        $this->uds->createTransactionByCode(HttpClientFake::CODE, ...$transactionData);
    }

    public function test_bad_request_exception()
    {
        $this->expectException(BadRequestException::class);
        $this->uds->createTransactionByUid(HttpClientFake::UID, []);
    }
}