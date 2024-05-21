<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

class MembershipTier
{
    /** Идентификатор статуса */
    public string $uid;

    /** Название статуса */
    public string $name;

    /** Коэффициент статуса */
    public float $rate;

    /** Процент счета, который можно оплатить бонусными баллами */
    public ?int $maxScoresDiscount;

    public function __construct(array $data)
    {
        $this->uid = $data['uid'];
        $this->name = $data['name'];
        $this->rate = $data['rate'];
        $this->maxScoresDiscount = $data['maxScoresDiscount'] ?? null;
    }
}