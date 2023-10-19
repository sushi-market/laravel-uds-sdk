<?php

namespace SushiMarket\UdsSdk\Resources;

class PurchaseCalc
{
    /** Максимальное количество бонусных баллов, доступное для списания */
    public float $maxPoints;

    /** Общая сумма счета */
    public float $total;

    /** Часть суммы счета, на которую не начисляется кешбэк и не распространяется скидка */
    public float $skipLoyaltyTotal;

    /** Часть суммы счета, которую нельзя погасить баллами */
    public float $unredeemableTotal;

    /** Размер скидки */
    public float $discountAmount;

    /** Предоставленная скидка */
    public float $discountPercent;

    /** Бонусных баллов к оплате */
    public float $points;

    /** Размер скидки за счет бонусных баллов */
    public float $pointsPercent;

    /** Общий размер скидки */
    public float $netDiscount;

    /** Общий размер скидки в (в процентах от общей суммы счета) */
    public float $netDiscountPercent;

    /** Количество списываемых бонусных баллов сертификата */
    public float $certificatePoints;

    /** Сумма к оплате */
    public float $cash;

    /** Итоговая сумма к оплате с учетом доставки */
    public float $cashTotal;

    /** Вознаграждение (кешбэк), которое получит клиент после проведения операции */
    public float $cashBack;

    /** Процент счета, который можно оплатить бонусными баллами */
    public float $maxScoresDiscount;

    public function __construct($data)
    {
        $this->maxPoints = $data['maxPoints'];
        $this->total = $data['total'];
        $this->skipLoyaltyTotal = $data['skipLoyaltyTotal'];
        $this->unredeemableTotal = $data['unredeemableTotal'];
        $this->discountAmount = $data['discountAmount'];
        $this->discountPercent = $data['discountPercent'];
        $this->points = $data['points'];
        $this->pointsPercent = $data['pointsPercent'];
        $this->netDiscount = $data['netDiscount'];
        $this->netDiscountPercent = $data['netDiscountPercent'];
        $this->certificatePoints = $data['certificatePoints'];
        $this->cash = $data['cash'];
        $this->cashTotal = $data['cashTotal'];
        $this->cashBack = $data['cashBack'];
        $this->maxScoresDiscount = $data['maxScoresDiscount'];
    }
}