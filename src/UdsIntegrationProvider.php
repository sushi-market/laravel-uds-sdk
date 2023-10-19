<?php

namespace SushiMarket\UdsSdk;

use Illuminate\Support\ServiceProvider;
use SushiMarket\UdsSdk\Interfaces\HttpClientInterface;
use SushiMarket\UdsSdk\Services\HttpClientFake;
use SushiMarket\UdsSdk\Services\HttpClientProduction;

class UdsIntegrationProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            HttpClientInterface::class,
            app()->environment('testing') ? HttpClientFake::class : HttpClientProduction::class,
        );
    }
}