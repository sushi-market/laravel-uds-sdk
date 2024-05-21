<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Enums;

enum Gender: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';
    case NOT_SPECIFIED = 'NOT_SPECIFIED';
}
