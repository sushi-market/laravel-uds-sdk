<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Test;

use SushiMarket\UdsSdk\Services\HttpClientFake;
use SushiMarket\UdsSdk\Uds;
use SushiMarket\UdsSdk\UdsIntegrationProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected Uds $uds;

    protected Uds $udsInvalid;

    protected Uds $udsServerError;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uds = app(Uds::class, [
            'companyId' => HttpClientFake::COMPANY_ID,
            'apiKey' => HttpClientFake::API_KEY,
        ]);

        $this->udsInvalid = app(Uds::class, [
            'companyId' => HttpClientFake::COMPANY_ID,
            'apiKey' => 'invalid-token',
        ]);

        $this->udsServerError = app(Uds::class, [
            'companyId' => HttpClientFake::COMPANY_ID,
            'apiKey' => HttpClientFake::SERVER_ERROR_API_KEY,
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            UdsIntegrationProvider::class,
        ];
    }
}
