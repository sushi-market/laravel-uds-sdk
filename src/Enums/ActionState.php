<?php

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
