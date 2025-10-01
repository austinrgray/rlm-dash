<?php

namespace App\Enums\Finance;

namespace App\Enums\Finance;

enum LineItemCategory: string
{
    case Service        = 'service';
    case Right          = 'right';
    case Product        = 'product';
    case Subscription   = 'subscription';
    case Adjustment     = 'adjustment';
}
