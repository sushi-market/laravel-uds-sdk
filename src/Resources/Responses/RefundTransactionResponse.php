<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources\Responses;

use SushiMarket\UdsSdk\Resources\Transaction;

class RefundTransactionResponse extends Transaction
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}