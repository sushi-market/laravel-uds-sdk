<?php

namespace SushiMarket\UdsSdk\Test;

use SushiMarket\UdsSdk\Exceptions\InternalServerErrorException;
use SushiMarket\UdsSdk\Exceptions\NotFoundException;
use SushiMarket\UdsSdk\Exceptions\UnauthorizedException;
use SushiMarket\UdsSdk\Resources\Responses\CalculateTransactionResponse;
use SushiMarket\UdsSdk\Resources\Responses\SettingsResponse;
use SushiMarket\UdsSdk\Services\HttpClientFake;
use SushiMarket\UdsSdk\Uds;

class CredentialsTest extends TestCase
{
    public function test_valid_is_true()
    {
        $this->assertTrue($this->uds->testCredentials());
    }

    public function test_invalid_is_false()
    {
        $this->assertFalse($this->udsInvalid->testCredentials());
    }

    public function test_server_error_exception()
    {
        $this->expectException(InternalServerErrorException::class);
        $this->udsServerError->testCredentials();
    }
}