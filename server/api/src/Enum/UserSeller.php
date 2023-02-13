<?php

namespace App\Enum;

enum UserSellerStatus: string
{
    case PENDING = "pending";
    case APPROVED = "approved";
    case REJECTED = "rejected";
}
