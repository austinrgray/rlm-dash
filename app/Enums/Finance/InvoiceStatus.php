<?php

namespace App\Enums\Finance;

enum InvoiceStatus: string
{
    case Open = 'open';
    case Overdue = 'overdue';
    case Partial = 'partial';
    case Paid = 'paid';
    case Void = 'void';
}
