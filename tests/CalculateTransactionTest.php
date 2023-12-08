<?php

namespace SushiMarket\UdsSdk\Test;

use InvalidArgumentException;
use SushiMarket\UdsSdk\Exceptions\BadRequestException;
use SushiMarket\UdsSdk\Exceptions\InternalServerErrorException;
use SushiMarket\UdsSdk\Exceptions\NotFoundException;
use SushiMarket\UdsSdk\Resources\Responses\CalculateTransactionResponse;
use SushiMarket\UdsSdk\Services\HttpClientFake;

class CalculateTransactionTest extends TestCase
{
    protected array $receipt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->receipt = [
            'total' => 1000,
            'skipLoyaltyTotal' => 0,
            'unredeemableTotal' => 0,
            'points' => 0,
        ];
    }

    public function test_by_code_is_instance_of_calculate_transaction_response()
    {
        $this->assertInstanceOf(CalculateTransactionResponse::class, $this->uds->calculateTransactionByCode(HttpClientFake::CODE, $this->receipt));
    }

    public function test_by_certificate_code_response_body_certificate_points_is_300()
    {
        $response = $this->uds->calculateTransactionByCode(HttpClientFake::CERTIFICATE_CODE, $this->receipt);
        $this->assertEquals(300.0, $response->purchase->certificatePoints);
    }

    public function test_by_code_equals_tags()
    {
        $response = $this->uds->calculateTransactionByCode(HttpClientFake::CODE, $this->receipt);

        $this->assertEquals(1997, $response->client->tags[0]->id);
        $this->assertEquals('Постоянный гость', $response->client->tags[0]->name);
        $this->assertEquals(1998, $response->client->tags[1]->id);
        $this->assertEquals('Тег 2', $response->client->tags[1]->name);
        $this->assertEquals(1999, $response->client->tags[2]->id);
        $this->assertEquals('Тег 3', $response->client->tags[2]->name);
    }

    public function test_by_invalid_code_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->calculateTransactionByCode('999999', $this->receipt);
    }

    public function test_by_phone_is_instance_of_calculate_transaction_response()
    {
        $this->assertInstanceOf(CalculateTransactionResponse::class, $this->uds->calculateTransactionByPhone(HttpClientFake::PHONE, $this->receipt));
    }

    public function test_by_phone_equals_tags()
    {
        $response = $this->uds->calculateTransactionByPhone(HttpClientFake::PHONE, $this->receipt);

        $this->assertEquals(1997, $response->client->tags[0]->id);
        $this->assertEquals('Постоянный гость', $response->client->tags[0]->name);
        $this->assertEquals(1998, $response->client->tags[1]->id);
        $this->assertEquals('Тег 2', $response->client->tags[1]->name);
        $this->assertEquals(1999, $response->client->tags[2]->id);
        $this->assertEquals('Тег 3', $response->client->tags[2]->name);
    }

    public function test_by_invalid_phone_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->calculateTransactionByPhone('+79999998888', $this->receipt);
    }

    public function test_by_valid_uid_is_instance_of_calculate_transaction_response()
    {
        $this->assertInstanceOf(CalculateTransactionResponse::class, $this->uds->calculateTransactionByUid(HttpClientFake::UID, $this->receipt));
    }

    public function test_by_valid_uid_equals_tags()
    {
        $response = $this->uds->calculateTransactionByUid(HttpClientFake::UID, $this->receipt);

        $this->assertEquals(1997, $response->client->tags[0]->id);
        $this->assertEquals('Постоянный гость', $response->client->tags[0]->name);
        $this->assertEquals(1998, $response->client->tags[1]->id);
        $this->assertEquals('Тег 2', $response->client->tags[1]->name);
        $this->assertEquals(1999, $response->client->tags[2]->id);
        $this->assertEquals('Тег 3', $response->client->tags[2]->name);
    }

    public function test_by_invalid_uid_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->calculateTransactionByUid('xxxxxxxx-invalid-user-uuid-xxxxxxxx', $this->receipt);
    }

    public function test_exception_if_empty_code_and_participant()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uds->calculateTransaction($this->receipt);
    }

    public function test_server_error_exception()
    {
        $this->expectException(InternalServerErrorException::class);
        $this->udsServerError->calculateTransactionByUid(HttpClientFake::UID, $this->receipt);
    }

    public function test_bad_request_exception()
    {
        $this->expectException(BadRequestException::class);
        $this->uds->calculateTransactionByUid(HttpClientFake::UID, []);
    }
}