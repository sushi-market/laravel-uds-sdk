<?php

namespace SushiMarket\UdsSdk\Resources;

class CustomerShortInfo
{
    /** ID клиента в компании */
    public int $id;

    /** Идентификатор клиента в UDS */
    public ?string $uid;

    /** Имя и фамилия клиента */
    public string $displayName;

    /** Настройки статуса клиента */
    public ?MembershipTier $baseMembershipTier;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->uid = $data['uid'] ?? null;
        $this->displayName = $data['displayName'];
        $this->baseMembershipTier = isset($data['membershipTier']) ? new MembershipTier($data['membershipTier']) : null;
    }
}