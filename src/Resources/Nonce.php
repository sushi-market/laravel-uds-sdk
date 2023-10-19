<?php

namespace SushiMarket\UdsSdk\Resources;

use Exception;
use Illuminate\Support\Str;

class Nonce
{
    /** UUID */
    protected string $uuid;

    /**
     * @throws Exception
     */
    public function __construct(string $uuid = null)
    {
        $uuid = $uuid ?? self::generateUuid();

        if (!self::isValidUuid($uuid)) throw new Exception('Invalid uuid');

        $this->uuid = self::generateUuid();
    }

    /** Генерация UUID */
    public static function generateUuid(): string
    {
        return Str::uuid()->toString();
    }

    /** Проверка UUID на валидность */
    public static function isValidUuid(string $uuid): bool
    {
        return Str::isUuid($uuid);
    }

    /** Получить UUD */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}