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
        $request_body = (string) $request->getBody();
        $response_body = $response ? (string) $response->getBody() : null;

        return json_encode([
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'status_code' => $response?->getStatusCode(),
            'request_headers' => $request->getHeaders(),
            'request_body' => json_validate($request_body) ? json_decode($request_body) : $request_body,
            'response_headers' => $response?->getHeaders(),
            'response_body' => json_validate($response_body) ? json_decode($response_body) : $response_body,
        ], JSON_UNESCAPED_UNICODE);
    }
}