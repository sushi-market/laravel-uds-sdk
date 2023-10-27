<?php

namespace SushiMarket\UdsSdk\Guzzle;

use GuzzleHttp\MessageFormatterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class MessageFormatterJson implements MessageFormatterInterface
{
    public function format(RequestInterface $request, ResponseInterface $response = null, Throwable $error = null): string
    {
        return json_encode([
            'request_headers' => $request->getHeaders(),
            'request_body' => $request->getBody()->getContents(),
            'response_headers' => $response->getHeaders(),
            'response_body' => $response->getBody()->getContents(),
            'status_code' => $response->getStatusCode()
        ]);
    }
}