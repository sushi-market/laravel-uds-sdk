<?php

namespace SushiMarket\UdsSdk\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Customer with given code or ID is not found.', 404);
    }
}