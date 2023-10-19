<?php

namespace SushiMarket\UdsSdk\Resources;

class ExternalCashier
{
    /** Внешний идентификатор сотрудника */
    public string $externalId;

    /** Имя сотрудника */
    public ?string $name;

    public function __construct(array $data)
    {
        $this->externalId = $data['externalId'];
        $this->name = $data['name'] ?? null;
    }
}