<?php
declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use SushiMarket\UdsSdk\Guzzle\MessageFormatterJson;
use SushiMarket\UdsSdk\Test\TestCase;

class MessageFormatterJsonTest extends TestCase
{
    public function test_null_response_response()
    {
        Http::fake(function (Request $request) {
            $logResponse = json_decode((new MessageFormatterJson)->format($request->toPsrRequest()));

            $this->assertNull($logResponse->status_code);
            $this->assertNull($logResponse->response_body);
            $this->assertNull($logResponse->response_headers);
        })->get('/');
    }
}