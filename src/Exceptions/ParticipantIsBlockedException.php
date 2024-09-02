<?php

namespace SushiMarket\UdsSdk\Exceptions;

use Exception;

class ParticipantIsBlockedException extends Exception
{
    public function __construct()
    {
        parent::__construct('This company has blocked you, but you can use UDS in other companies.', 400);
    }
}