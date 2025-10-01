<?php

namespace App\Enums\Finance;

enum PaymentMethodType: string
{
    case Cash = 'cash';
    case Check = 'check';
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';
    case Other = 'other';
    case Unknown = 'unknown';
}
