<?php

namespace App\Enums\Cemetery;

enum BurialRightStatus: string
{
    case Active = 'active';
    case Transferred = 'transferred';
    case Void = 'void';
}
