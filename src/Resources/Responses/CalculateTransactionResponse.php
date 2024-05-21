<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources\Responses;

use SushiMarket\UdsSdk\Resources\CustomerDetail;
use SushiMarket\UdsSdk\Resources\PurchaseCalc;

class CalculateTransactionResponse
{
    /** Информация о клиенте */
    public CustomerDetail $client;

    /** Информация об операции */
    public PurchaseCalc $purchase;

    public function __construct(array $data) {
        $this->client = new CustomerDetail($data['user']);
        $this->purchase = new PurchaseCalc($data['purchase']);
    }
}