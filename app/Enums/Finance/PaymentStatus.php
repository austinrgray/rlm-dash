<?php

namespace App\Enums\Finance;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Settled = 'settled';
    case Failed = 'failed';
}
