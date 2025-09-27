<?php

namespace App\Enums;

enum FamilyRole: string
{
    case HeadOfFamily = 'head_of_family';
    case Spouse = 'spouse';
    case Parent = 'parent';
    case Child = 'child';
    case Executor = 'executor';
    case Other = 'other';
}
