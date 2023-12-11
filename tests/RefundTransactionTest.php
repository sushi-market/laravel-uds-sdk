<?php

namespace SushiMarket\UdsSdk\Test;

use SushiMarket\UdsSdk\Exceptions\InternalServerErrorException;
use SushiMarket\UdsSdk\Exceptions\NotFoundException;
use SushiMarket\UdsSdk\Resources\Responses\RefundTransactionResponse;
use SushiMarket\UdsSdk\Services\HttpClientFake;

class RefundTransactionTest extends TestCase
{
    public function test_is_instance_of_refund_transaction_response()
    {
        $this->assertInstanceOf(RefundTransactionResponse::class, $this->uds->refundTransaction(HttpClientFake::TRANSACTION_ID, 100));
    }

    public function test_response_body_is_not_certificate()
    {
        $response = $this->uds->refundTransaction(HttpClientFake::TRANSACTION_ID, 100);
        $this->assertFalse($response->isCertificate);
    }

    public function test_certificate_response_body_is_certificate()
    {
        $response = $this->uds->refundTransaction(HttpClientFake::TRANSACTION_ID_WITH_CERTIFICATE, 100);
        $this->assertTrue($response->isCertificate);
    }

    public function test_invalid_id_throw_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->uds->refundTransaction(333, 100);
    }

    public function test_server_error_exception()
    {
        $this->expectException(InternalServerErrorException::class);
        $this->udsServerError->refundTransaction(HttpClientFake::TRANSACTION_ID, 100);
    }
}