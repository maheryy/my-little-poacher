<?php

namespace App\Enum;

enum TicketStatus: string
{
    case PENDING = "pending";
    case CANCELLED = "cancelled";
    case CONFIRMED = "confirmed";
    case EXPIRED = "expired";
    case USED = "used";
}
