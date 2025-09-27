<?php

namespace App\Enums;

enum DispositionType: string
{
    case TraditionalBurial = 'traditional_burial';
    case CremationBurial = 'cremation_burial';
    case Mausoleum = 'mausoleum';
    case Columbarium = 'columbarium';
    case Other = 'other';
    case Unknown = 'unknown';
}
