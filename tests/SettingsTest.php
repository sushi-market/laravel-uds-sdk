<?php

namespace SushiMarket\UdsSdk\Test;

use SushiMarket\UdsSdk\Exceptions\InternalServerErrorException;
use SushiMarket\UdsSdk\Resources\Responses\SettingsResponse;
use SushiMarket\UdsSdk\Services\HttpClientFake;

class SettingsTest extends TestCase
{
    public function test_is_instance_of_settings_response()
    {
        $this->assertInstanceOf(SettingsResponse::class, $this->uds->getSettings());
    }

    public function test_company_id_is_fake()
    {
        $this->assertEquals(HttpClientFake::COMPANY_ID, $this->uds->getSettings()->id);
    }

    public function test_company_slug_is_fake()
    {
        $this->assertEquals('test-slug', $this->uds->getSettings()->slug);
    }

    public function test_server_error_exception()
    {
        $this->expectException(InternalServerErrorException::class);
        $this->udsServerError->getSettings();
    }
}