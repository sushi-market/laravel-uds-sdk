<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Services;

use DateTime;
use DateTimeInterface;
use GuzzleHttp\Middleware;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use SushiMarket\UdsSdk\Exceptions\BadRequestException;
use SushiMarket\UdsSdk\Exceptions\InternalServerErrorException;
use SushiMarket\UdsSdk\Exceptions\InvalidCheckSumException;
use SushiMarket\UdsSdk\Exceptions\NotFoundException;
use SushiMarket\UdsSdk\Exceptions\ParticipantIsBlockedException;
use SushiMarket\UdsSdk\Exceptions\UnauthorizedException;
use SushiMarket\UdsSdk\Guzzle\MessageFormatterJson;
use SushiMarket\UdsSdk\Interfaces\HttpClientInterface;

class HttpClientProduction implements HttpClientInterface
{
    protected PendingRequest $client;

    public function __construct(string $baseUrl, string $companyId, string $token)
    {
        $this->client = Http::baseUrl($baseUrl)->withBasicAuth($companyId, $token);
        $this->enableThrowing()->enableDebugHeaders()->enableLogging();
    }

    public function getClient(): PendingRequest
    {
        return $this->client;
    }

    public function getLogger(): LoggerInterface
    {
        return Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/laravel-uds-sdk/http-client.log'),
            'days' => 14,
        ]);
    }

    public function enableLogging(): self
    {
        $this->getClient()->withMiddleware(Middleware::log(
            $this->getLogger(),
            new MessageFormatterJson()
        ));

        return $this;
    }

    public function enableDebugHeaders(): self
    {
        $this->getClient()->withHeaders([
            'X-Origin-Request-Id' => Str::uuid()->toString(),
            'X-Timestamp' => (new DateTime())->format(DateTimeInterface::ATOM)
        ]);

        return $this;
    }

    public function enableThrowing(): self
    {
        $this->getClient()->throw(fn(Response $response, RequestException $e) => $this->exceptionHandler(...func_get_args()));
        return $this;
    }

    /**
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws RequestException
     * @throws InternalServerErrorException
     * @throws UnauthorizedException
     * @throws InvalidCheckSumException
     * @throws ParticipantIsBlockedException
     */
    public function exceptionHandler(Response $response, RequestException $e): void
    {
        if ($response->status() >= 500) {
            throw new InternalServerErrorException($e->getMessage(), $e->response->status());
        }

        $json = $response->json();
        if (isset($json['errorCode'])) {
            throw match($json['errorCode']) {
                'badRequest' => new BadRequestException(),
                'unauthorized' => new UnauthorizedException(),
                'participantIsBlocked' => new ParticipantIsBlockedException(),
                'notFound' => new NotFoundException(),
                'invalidChecksum' => new InvalidCheckSumException(),
                default => $e
            };
        }
    }
}