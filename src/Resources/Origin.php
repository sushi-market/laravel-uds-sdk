<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

class Origin
{
    /** Идентификатор исходной (оригинальной) транзакции */
    public int $id;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
    }
}