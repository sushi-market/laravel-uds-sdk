<?php

namespace SushiMarket\UdsSdk\Resources;

class Cashier
{
    /** ID сотрудника в UDS */
    public int $id;

    /** Имя сотрудника */
    public string $displayName;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->displayName = $data['displayName'];
    }
}