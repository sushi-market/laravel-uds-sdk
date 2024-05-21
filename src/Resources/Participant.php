<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

use Illuminate\Support\Carbon;

class Participant
{
    /** ID клиента в компании */
    public ?int $id;

    /** ID клиента в компании, пригласившего данного клиента */
    public ?int $inviterId;

    /** Баланс бонусных баллов клиента */
    public ?float $points;

    /** Размер скидки (в процентах) */
    public ?float $discountRate;

    /** Размер кешбэка (в процентах) */
    public ?float $cashbackRate;

    /** Настройки статусов клиентов */
    public MembershipTier $membershipTier;

    /** Дата, когда клиент присоединился к компании */
    public ?Carbon $dataCreated;

    /** Дата, когда клиент совершил последнюю транзакцию */
    public ?Carbon $lastTransactionTime;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->inviterId = $data['inviterId'] ?? null;
        $this->points = $data['points'] ?? null;
        $this->discountRate = $data['discountRate'] ?? null;
        $this->cashbackRate = $data['cashbackRate'] ?? null;
        $this->membershipTier = new MembershipTier($data['membershipTier']);
        $this->dataCreated = isset($data['dataCreated']) ? Carbon::parse($data['dataCreated']) : null;
        $this->lastTransactionTime = Carbon::parse($data['lastTransactionTime']) ?? null;
    }
}