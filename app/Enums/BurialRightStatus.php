<?php

namespace App\Enums;

enum BurialRightStatus: string
{
    case Active = 'active';
    case Transferred = 'transferred';
    case Void = 'void';
}
