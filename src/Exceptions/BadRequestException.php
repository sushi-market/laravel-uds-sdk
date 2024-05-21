<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    public function __construct()
    {
        parent::__construct('Form validation errors occurred.', 400);
    }
}