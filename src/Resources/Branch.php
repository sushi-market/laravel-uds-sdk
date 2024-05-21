<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

class Branch
{
    /** ID филиала в UDS */
    public int $id;

    /** Название филиала */
    public string $displayName;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->displayName = $data['displayName'];
    }
}