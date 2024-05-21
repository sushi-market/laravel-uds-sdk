<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct()
    {
        parent::__construct('API key or company id are incorrect.', 401);
    }
}