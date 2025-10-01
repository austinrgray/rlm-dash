<?php

namespace App\Enums\Customer;

enum OrganizationType: string
{
    case Business = 'business';
    case Charity = 'charity';
    case Church = 'church';
    case Government = 'government';
    case School = 'school';
    case Other = 'other';
}
