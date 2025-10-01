<?php

namespace App\Enums\Customer;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';
    case Unknown = 'unknown';
}
