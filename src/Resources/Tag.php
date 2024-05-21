<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

class Tag
{
    /** Идентификатор тега */
    public int $id;

    /** Наименование тега */
    public string $name;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
    }
}