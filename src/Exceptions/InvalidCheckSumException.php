<?php

namespace SushiMarket\UdsSdk\Exceptions;

use Exception;

class InvalidCheckSumException extends Exception
{
    public function __construct()
    {
        parent::__construct('Given total, cash and points fields don\'t correlate with company marketing settings in UDS.', 400);
    }
}