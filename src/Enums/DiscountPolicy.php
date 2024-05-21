<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Enums;

enum DiscountPolicy: string
{
    /** Понижать сумму счета (скидка) */
    case APPLY_DISCOUNT = 'APPLY_DISCOUNT';

    /** Начислять бонусные баллы (кешбэк) */
    case CHARGE_SCORES = 'CHARGE_SCORES';
}
