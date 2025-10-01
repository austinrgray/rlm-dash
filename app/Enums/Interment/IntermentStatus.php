<?php

namespace App\Enums\Interment;

enum IntermentStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
