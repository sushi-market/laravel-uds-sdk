<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Enums;

enum ActionState: string
{
    /** Нормальный */
    case NORMAL = 'NORMAL';

    /** Отменённый */
    case CANCELED = 'CANCELED';

    /** Возвращенный */
    case REVERSAL = 'REVERSAL';
}
