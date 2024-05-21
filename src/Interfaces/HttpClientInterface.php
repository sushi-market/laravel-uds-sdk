<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Interfaces;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Psr\Log\LoggerInterface;

interface HttpClientInterface {
    function getClient(): PendingRequest;

    public function getLogger(): LoggerInterface;

    public function enableLogging(): self;

    public function enableDebugHeaders(): self;

    public function enableThrowing(): self;

    public function exceptionHandler(Response $response, RequestException $e): void;
}
