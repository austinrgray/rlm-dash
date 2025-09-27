<?php

namespace App\Enums;

enum VaultType: string
{
    case Concrete = 'concrete';
    case Steel = 'steel';
    case Bronze = 'bronze';
    case UrnCremation = 'urn_cremation';
    case NoneCremation = 'none_cremation';
    case Other = 'other';
    case Unknown = 'unknown';
}
