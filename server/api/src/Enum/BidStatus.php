<?php

namespace App\Enum;

enum BidStatus: string
{
    case PENDING = "pending";
    case DONE = "done";
    case PAID = "paid";
}
