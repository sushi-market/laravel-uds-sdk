<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

class LoyaltyProgramSettings
{
    /** Настройки статусов клиентов */
    public MembershipTier $baseMembershipTier;

    /** Настройки статусов */
    public array $membershipTiers;

    /** Коэффициенты начисления кэшбэка для рефералов (3 уровня в %) */
    public array $referralCashbackRates;

    /** Процент вознаграждения кассиру за проведенную операцию */
    public ?float $cashierAward;

    /** Вознаграждение клиенту за эффективную рекомендацию */
    public ?float $referralReward;

    /** Максимальная сумма операции, которую можно провести через UDS Кассир */
    public ?float $receiptLimit;

    /** Количество дней, после которых будут начислены отложенные бонусные баллы */
    public ?float $deferPointsForDays;

    /** Количество баллов за первую покупку */
    public ?float $firstPurchasePoints;

    public function __construct(array $data)
    {
        $this->baseMembershipTier = new MembershipTier($data['baseMembershipTier']);
        $this->membershipTiers = array_reduce(
            $data['membershipTiers'],
            function (array $out, array $item) {
                $out[] = new MembershipTier($item);
                return $out;
            },
            []
        );
        $this->referralCashbackRates = $data['referralCashbackRates'];
        $this->cashierAward = $data['cashierAward'] ?? null;
        $this->referralReward = $data['referralReward'] ?? null;
        $this->receiptLimit = $data['receiptLimit'] ?? null;
        $this->deferPointsForDays = $data['deferPointsForDays'] ?? null;
        $this->firstPurchasePoints = $data['firstPurchasePoints'] ?? null;
    }
}